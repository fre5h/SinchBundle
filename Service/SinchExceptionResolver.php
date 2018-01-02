<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fresh\SinchBundle\Service;

use Fresh\SinchBundle\Exception\BadRequest\SinchInvalidRequestException;
use Fresh\SinchBundle\Exception\BadRequest\SinchMissingParameterException;
use Fresh\SinchBundle\Exception\BadRequest\SinchParameterValidationException;
use Fresh\SinchBundle\Exception\Forbidden\SinchForbiddenRequestException;
use Fresh\SinchBundle\Exception\Forbidden\SinchInvalidAuthorizationSchemeException;
use Fresh\SinchBundle\Exception\Forbidden\SinchNoVerifiedPhoneNumberException;
use Fresh\SinchBundle\Exception\InternalServerError\SinchInternalErrorException;
use Fresh\SinchBundle\Exception\PaymentRequired\SinchPaymentRequiredException;
use Fresh\SinchBundle\Exception\SinchException;
use Fresh\SinchBundle\Exception\Unauthorized\SinchIllegalAuthorizationHeaderException;
use Fresh\SinchBundle\Helper\SinchErrorCode;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

/**
 * SinchExceptionResolver.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SinchExceptionResolver
{
    /**
     * @param ClientException $e
     *
     * @return \Exception|SinchException
     */
    public static function createAppropriateSinchException(ClientException $e): \Exception
    {
        $response = \json_decode($e->getResponse()->getBody()->getContents(), true);
        $responseStatusCode = $e->getCode();

        $errorCode = (int) $response['errorCode'];
        $errorMessage = $response['message'];

        $exception = null;

        switch ($responseStatusCode) {
            case Response::HTTP_BAD_REQUEST:
                $exception = self::getSinchExceptionForBadRequest($errorCode, $errorMessage);

                break;
            case Response::HTTP_UNAUTHORIZED:
                $exception = self::getSinchExceptionForUnauthorized($errorCode, $errorMessage);

                break;
            case Response::HTTP_PAYMENT_REQUIRED:
                $exception = self::getSinchExceptionForPaymentRequired($errorCode, $errorMessage);

                break;
            case Response::HTTP_FORBIDDEN:
                $exception = self::getSinchExceptionForForbidden($errorCode, $errorMessage);

                break;
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                $exception = self::getSinchExceptionForInternalServerError($errorCode, $errorMessage);

                break;
        }

        if (null === $exception) {
            $exception = new \Exception('Unknown Sinch Error Code');
        }

        return $exception;
    }

    /**
     * @param int    $errorCode
     * @param string $errorMessage
     *
     * @return SinchException|null
     */
    private static function getSinchExceptionForBadRequest(int $errorCode, string $errorMessage): ?SinchException
    {
        $exception = null;

        switch ($errorCode) {
            case SinchErrorCode::PARAMETER_VALIDATION:
                $exception = new SinchParameterValidationException($errorMessage);

                break;
            case SinchErrorCode::MISSING_PARAMETER:
                $exception = new SinchMissingParameterException($errorMessage);

                break;
            case SinchErrorCode::INVALID_REQUEST:
                $exception = new SinchInvalidRequestException($errorMessage);

                break;
        }

        return $exception;
    }

    /**
     * @param int    $errorCode
     * @param string $errorMessage
     *
     * @return SinchException|null
     */
    private static function getSinchExceptionForUnauthorized(int $errorCode, string $errorMessage): ?SinchException
    {
        $exception = null;

        if (SinchErrorCode::ILLEGAL_AUTHORIZATION_HEADER === $errorCode) {
            $exception = new SinchIllegalAuthorizationHeaderException($errorMessage);
        }

        return $exception;
    }

    /**
     * Sinch returns 402 code when application runs out of money.
     *
     * @param int    $errorCode
     * @param string $errorMessage
     *
     * @return SinchException|null
     */
    private static function getSinchExceptionForPaymentRequired(int $errorCode, string $errorMessage): ?SinchException
    {
        $exception = null;

        if (SinchErrorCode::THERE_IS_NOT_ENOUGH_FUNDS_TO_SEND_THE_MESSAGE === $errorCode) {
            $exception = new SinchPaymentRequiredException($errorMessage);
        }

        return $exception;
    }

    /**
     * @param int    $errorCode
     * @param string $errorMessage
     *
     * @return SinchException|null
     */
    private static function getSinchExceptionForForbidden(int $errorCode, string $errorMessage): ?SinchException
    {
        $exception = null;

        switch ($errorCode) {
            case SinchErrorCode::FORBIDDEN_REQUEST:
                $exception = new SinchForbiddenRequestException($errorMessage);

                break;
            case SinchErrorCode::INVALID_AUTHORIZATION_SCHEME_FOR_CALLING_THE_METHOD:
                $exception = new SinchInvalidAuthorizationSchemeException($errorMessage);

                break;
            case SinchErrorCode::NO_VERIFIED_PHONE_NUMBER_ON_YOUR_SINCH_ACCOUNT:
            case SinchErrorCode::SANDBOX_SMS_ONLY_ALLOWED_TO_BE_SENT_TO_VERIFIED_NUMBERS:
                $exception = new SinchNoVerifiedPhoneNumberException($errorMessage);

                break;
        }

        return $exception;
    }

    /**
     * @param int    $errorCode
     * @param string $errorMessage
     *
     * @return SinchException|null
     */
    private static function getSinchExceptionForInternalServerError(int $errorCode, string $errorMessage): ?SinchException
    {
        $exception = null;

        if (SinchErrorCode::INTERNAL_ERROR === $errorCode) {
            $exception = new SinchInternalErrorException($errorMessage);
        }

        return $exception;
    }

    // endregion
}
