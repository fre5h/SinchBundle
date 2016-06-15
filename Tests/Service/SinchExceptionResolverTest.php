<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Service;

use Fresh\SinchBundle\Exception\BadRequest\SinchInvalidRequestException;
use Fresh\SinchBundle\Exception\BadRequest\SinchMissingParameterException;
use Fresh\SinchBundle\Exception\BadRequest\SinchParameterValidationException;
use Fresh\SinchBundle\Exception\Forbidden\SinchForbiddenRequestException;
use Fresh\SinchBundle\Exception\Forbidden\SinchInvalidAuthorizationSchemeException;
use Fresh\SinchBundle\Exception\Forbidden\SinchNoVerifiedPhoneNumberException;
use Fresh\SinchBundle\Exception\InternalServerError\SinchInternalErrorException;
use Fresh\SinchBundle\Exception\PaymentRequired\SinchPaymentRequiredException;
use Fresh\SinchBundle\Exception\Unauthorized\SinchIllegalAuthorizationHeaderException;
use Fresh\SinchBundle\Helper\SinchErrorCode;
use Fresh\SinchBundle\Service\SinchExceptionResolver;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * FreshSinchExtensionTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchExceptionResolverTest extends \PHPUnit_Framework_TestCase
{
    // region Bad Request exceptions

    public function testSinchParameterValidationException()
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::PARAMETER_VALIDATION);
        $this->assertInstanceOf(SinchParameterValidationException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchInvalidRequestException()
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::INVALID_REQUEST);
        $this->assertInstanceOf(SinchInvalidRequestException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchMissingParameterException()
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::MISSING_PARAMETER);
        $this->assertInstanceOf(SinchMissingParameterException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    // endregion

    // region Unauthorized exceptions

    public function testSinchIllegalAuthorizationHeaderException()
    {
        $e = $this->getClientException(Response::HTTP_UNAUTHORIZED, SinchErrorCode::ILLEGAL_AUTHORIZATION_HEADER);
        $this->assertInstanceOf(SinchIllegalAuthorizationHeaderException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    // endregion

    // region Payment Required exceptions

    public function testSinchPaymentRequiredException()
    {
        $e = $this->getClientException(Response::HTTP_PAYMENT_REQUIRED, SinchErrorCode::THERE_IS_NOT_ENOUGH_FUNDS_TO_SEND_THE_MESSAGE);
        $this->assertInstanceOf(SinchPaymentRequiredException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    // endregion

    // region Forbidden exceptions

    public function testSinchForbiddenRequestException()
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::FORBIDDEN_REQUEST);
        $this->assertInstanceOf(SinchForbiddenRequestException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchInvalidAuthorizationSchemeException()
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::INVALID_AUTHORIZATION_SCHEME_FOR_CALLING_THE_METHOD);
        $this->assertInstanceOf(SinchInvalidAuthorizationSchemeException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchNoVerifiedPhoneNumberException()
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::NO_VERIFIED_PHONE_NUMBER_ON_YOUR_SINCH_ACCOUNT);
        $this->assertInstanceOf(SinchNoVerifiedPhoneNumberException::class, SinchExceptionResolver::createAppropriateSinchException($e));

        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::SANDBOX_SMS_ONLY_ALLOWED_TO_BE_SENT_TO_VERIFIED_NUMBERS);
        $this->assertInstanceOf(SinchNoVerifiedPhoneNumberException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    // endregion

    // region Internal Server Error exceptions

    public function testSinchInternalErrorException()
    {
        $e = $this->getClientException(Response::HTTP_INTERNAL_SERVER_ERROR, SinchErrorCode::INTERNAL_ERROR);
        $this->assertInstanceOf(SinchInternalErrorException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    // endregion

    // region Standard exceptions

    public function testStandardException()
    {
        $e = $this->getClientException(Response::HTTP_GATEWAY_TIMEOUT, 0);
        $this->assertInstanceOf(\Exception::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    // endregion

    /**
     * Get client exception
     *
     * @param int $statusCode Status code
     * @param int $errorCode  Error code
     *
     * @return ClientException
     */
    private function getClientException($statusCode, $errorCode)
    {
        $request  = $this->getMockBuilder(RequestInterface::class)->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $body     = $this->getMockBuilder(StreamInterface::class)->disableOriginalConstructor()->getMock();

        $response->expects($this->once())->method('getStatusCode')->willReturn($statusCode);
        $response->expects($this->once())->method('getBody')->will($this->returnValue($body));

        $body->expects($this->once())->method('getContents')->will($this->returnValue(<<<JSON
{
    "errorCode": $errorCode,
    "message": "Some message"
}
JSON
        ));

        return new ClientException(null, $request, $response);
    }
}
