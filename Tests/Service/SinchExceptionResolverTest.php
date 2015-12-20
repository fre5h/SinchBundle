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

use Fresh\SinchBundle\Helper\SinchErrorCode;
use Fresh\SinchBundle\Service\SinchExceptionResolver;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

/**
 * FreshSinchExtensionTest
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchExceptionResolverTest extends \PHPUnit_Framework_TestCase
{
    // region Bad Request exceptions

    /**
     * Test SinchParameterValidationException
     */
    public function testSinchParameterValidationException()
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::PARAMETER_VALIDATION);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\BadRequest\SinchParameterValidationException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    /**
     * Test SinchInvalidRequestException
     */
    public function testSinchInvalidRequestException()
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::INVALID_REQUEST);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\BadRequest\SinchInvalidRequestException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    /**
     * Test SinchMissingParameterException
     */
    public function testSinchMissingParameterException()
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::MISSING_PARAMETER);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\BadRequest\SinchMissingParameterException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    // endregion

    // region Unauthorized exceptions

    /**
     * Test SinchIllegalAuthorizationHeaderException
     */
    public function testSinchIllegalAuthorizationHeaderException()
    {
        $e = $this->getClientException(Response::HTTP_UNAUTHORIZED, SinchErrorCode::ILLEGAL_AUTHORIZATION_HEADER);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\Unauthorized\SinchIllegalAuthorizationHeaderException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    // endregion

    // region Payment Required exceptions

    /**
     * Test SinchPaymentRequiredException
     */
    public function testSinchPaymentRequiredException()
    {
        $e = $this->getClientException(Response::HTTP_PAYMENT_REQUIRED, SinchErrorCode::THERE_IS_NOT_ENOUGH_FUNDS_TO_SEND_THE_MESSAGE);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\PaymentRequired\SinchPaymentRequiredException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    // endregion

    // region Forbidden exceptions

    /**
     * Test SinchForbiddenRequestException
     */
    public function testSinchForbiddenRequestException()
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::FORBIDDEN_REQUEST);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\Forbidden\SinchForbiddenRequestException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    /**
     * Test SinchInvalidAuthorizationSchemeException
     */
    public function testSinchInvalidAuthorizationSchemeException()
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::INVALID_AUTHORIZATION_SCHEME_FOR_CALLING_THE_METHOD);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\Forbidden\SinchInvalidAuthorizationSchemeException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    /**
     * Test SinchNoVerifiedPhoneNumberException
     */
    public function testSinchNoVerifiedPhoneNumberException()
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::NO_VERIFIED_PHONE_NUMBER_ON_YOUR_SINCH_ACCOUNT);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\Forbidden\SinchNoVerifiedPhoneNumberException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );

        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::SANDBOX_SMS_ONLY_ALLOWED_TO_BE_SENT_TO_VERIFIED_NUMBERS);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\Forbidden\SinchNoVerifiedPhoneNumberException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    // endregion

    // region Internal Server Error exceptions

    /**
     * Test SinchInternalErrorException
     */
    public function testSinchInternalErrorException()
    {
        $e = $this->getClientException(Response::HTTP_INTERNAL_SERVER_ERROR, SinchErrorCode::INTERNAL_ERROR);

        $this->assertInstanceOf(
            '\Fresh\SinchBundle\Exception\InternalServerError\SinchInternalErrorException',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
    }

    // endregion

    // region Standard exceptions

    /**
     * Test standard exception
     */
    public function testStandardException()
    {
        $e = $this->getClientException(Response::HTTP_GATEWAY_TIMEOUT, 0);

        $this->assertInstanceOf(
            '\Exception',
            SinchExceptionResolver::createAppropriateSinchException($e)
        );
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
        $request  = $this->getMockBuilder('Psr\Http\Message\RequestInterface')->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')->disableOriginalConstructor()->getMock();
        $body     = $this->getMockBuilder('Psr\Http\Message\StreamInterface')->disableOriginalConstructor()->getMock();

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
