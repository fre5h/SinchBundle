<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
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
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * FreshSinchExtensionTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SinchExceptionResolverTest extends TestCase
{
    public function testSinchParameterValidationException(): void
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::PARAMETER_VALIDATION);
        self::assertInstanceOf(SinchParameterValidationException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchInvalidRequestException(): void
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::INVALID_REQUEST);
        self::assertInstanceOf(SinchInvalidRequestException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchMissingParameterException(): void
    {
        $e = $this->getClientException(Response::HTTP_BAD_REQUEST, SinchErrorCode::MISSING_PARAMETER);
        self::assertInstanceOf(SinchMissingParameterException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchIllegalAuthorizationHeaderException(): void
    {
        $e = $this->getClientException(Response::HTTP_UNAUTHORIZED, SinchErrorCode::ILLEGAL_AUTHORIZATION_HEADER);
        self::assertInstanceOf(SinchIllegalAuthorizationHeaderException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchPaymentRequiredException(): void
    {
        $e = $this->getClientException(Response::HTTP_PAYMENT_REQUIRED, SinchErrorCode::THERE_IS_NOT_ENOUGH_FUNDS_TO_SEND_THE_MESSAGE);
        self::assertInstanceOf(SinchPaymentRequiredException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchForbiddenRequestException(): void
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::FORBIDDEN_REQUEST);
        self::assertInstanceOf(SinchForbiddenRequestException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchInvalidAuthorizationSchemeException(): void
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::INVALID_AUTHORIZATION_SCHEME_FOR_CALLING_THE_METHOD);
        self::assertInstanceOf(SinchInvalidAuthorizationSchemeException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchNoVerifiedPhoneNumberException(): void
    {
        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::NO_VERIFIED_PHONE_NUMBER_ON_YOUR_SINCH_ACCOUNT);
        self::assertInstanceOf(SinchNoVerifiedPhoneNumberException::class, SinchExceptionResolver::createAppropriateSinchException($e));

        $e = $this->getClientException(Response::HTTP_FORBIDDEN, SinchErrorCode::SANDBOX_SMS_ONLY_ALLOWED_TO_BE_SENT_TO_VERIFIED_NUMBERS);
        self::assertInstanceOf(SinchNoVerifiedPhoneNumberException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testSinchInternalErrorException(): void
    {
        $e = $this->getClientException(Response::HTTP_INTERNAL_SERVER_ERROR, SinchErrorCode::INTERNAL_ERROR);
        self::assertInstanceOf(SinchInternalErrorException::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    public function testStandardException(): void
    {
        $e = $this->getClientException(Response::HTTP_GATEWAY_TIMEOUT, 0);
        self::assertInstanceOf(\Exception::class, SinchExceptionResolver::createAppropriateSinchException($e));
    }

    /**
     * @param int $statusCode Status code
     * @param int $errorCode  Error code
     *
     * @return ClientException
     */
    private function getClientException($statusCode, $errorCode): ClientException
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $body = $this->createMock(StreamInterface::class);

        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn($statusCode)
        ;
        $response
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($body)
        ;

        $body
            ->expects(self::once())
            ->method('getContents')
            ->willReturn(<<<JSON
                {
                    "errorCode": $errorCode,
                    "message": "Some message"
                }
            JSON)
        ;

        return new ClientException(null, $request, $response);
    }
}
