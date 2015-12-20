<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Service;

use Fresh\SinchBundle\Exception\BadRequest\SinchInvalidRequestException;
use Fresh\SinchBundle\Exception\BadRequest\SinchMissingParameterException;
use Fresh\SinchBundle\Exception\InternalServerError\SinchForbiddenRequestException;
use Fresh\SinchBundle\Exception\InternalServerError\SinchInternalErrorException;
use Fresh\SinchBundle\Exception\InternalServerError\SinchInvalidAuthorizationSchemeException;
use Fresh\SinchBundle\Exception\InternalServerError\SinchNoVerifiedPhoneNumberException;
use Fresh\SinchBundle\Exception\InternalServerError\SinchParameterValidationException;
use Fresh\SinchBundle\Exception\PaymentRequired\SinchPaymentRequiredException;
use Fresh\SinchBundle\Exception\Unauthorized\SinchIllegalAuthorizationHeaderException;
use Fresh\SinchBundle\Sms\SmsStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

/**
 * SinchService
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchService
{
    /**
     * @var Client $guzzleHTTPClient Guzzle HTTP client
     */
    private $guzzleHTTPClient;

    /**
     * @var string $host Host
     */
    private $host;

    /**
     * @var string $key Key
     */
    private $key;

    /**
     * @var string $secret Secret
     */
    private $secret;

    /**
     * @var string $from From
     */
    private $from;

    /**
     * Constructor
     *
     * @param string      $host   Host
     * @param string      $key    Key
     * @param string      $secret Secret
     * @param string|null $from   From
     */
    public function __construct($host, $key, $secret, $from = null)
    {
        $this->host   = $host;
        $this->key    = $key;
        $this->secret = $secret;
        $this->from   = $from;

        $this->guzzleHTTPClient = new Client([
            'base_uri' => rtrim($this->host, '/'),
        ]);
    }

    // region Public API

    /**
     * Send SMS
     *
     * @param string      $phoneNumber Phone number
     * @param string      $messageText Message text
     * @param string|null $from        From
     *
     * @return int Message ID
     *
     * @throws GuzzleException
     */
    public function sendSMS($phoneNumber, $messageText, $from = null)
    {
        $uri = '/v1/sms/'.$phoneNumber; // @todo validate phone number

        $body = [
            'auth'    => [$this->key, $this->secret],
            'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')], // ISO 8601 date format
            'json'    => ['message' => $messageText],
        ];

        if (null !== $from) {
            $body['json']['from'] = $from;
        } elseif (null !== $this->from) {
            $body['json']['from'] = $this->from;
        }

        try {
            $response = $this->guzzleHTTPClient->post($uri, $body);
        } catch (ClientException $e) {
            throw $this->createAppropriateSinchException($e);
        }

        $messageId = null;
        if (Response::HTTP_OK === $response->getStatusCode() && $response->hasHeader('Content-Type') &&
            'application/json; charset=utf-8' === $response->getHeaderLine('Content-Type')
        ) {
            $content = $response->getBody()->getContents();
            $content = json_decode($content, true);

            if (isset($content['messageId']) && array_key_exists('messageId', $content)) {
                $messageId = $content['messageId'];
            }
        };

        return $messageId;
    }

    /**
     * Get status of sent SMS
     *
     * Available SMS statuses: Successful, Pending, Faulted, Unknown
     *
     * @param int $messageId Message ID
     *
     * @return string SMS status
     *
     * @throws GuzzleException
     */
    public function getStatusOfSMS($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);
        $result = '';

        if (isset($response['status']) && array_key_exists('status', $response)) {
            $result = $response['status'];
        }

        return $result;
    }

    // endregion

    // region Check status helper methods

    /**
     * Returns true if SMS with some ID was sent successfully, otherwise returns false
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS was sent successfully, otherwise - false
     */
    public function smsIsSentSuccessfully($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SmsStatus::SUCCESSFUL === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns true if SMS with some ID is still pending, otherwise returns false
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS is still pending, otherwise - false
     */
    public function smsIsPending($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SmsStatus::PENDING === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns true if SMS with some ID was faulted, otherwise returns false
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS was faulted, otherwise - false
     */
    public function smsIsFaulted($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SmsStatus::FAULTED === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns true if SMS with some ID in unknown status, otherwise returns false
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS in unknown status, otherwise - false
     */
    public function smsInUnknownStatus($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SmsStatus::UNKNOWN === $response['status']) {
            $result = true;
        }

        return $result;
    }

    // endregion

    // region Private functions

    /**
     * Send request to check status of SMS
     *
     * @param int $messageId Message ID
     *
     * @return array|null
     *
     * @throws SinchPaymentRequiredException When run out of money
     */
    private function sendRequestToCheckStatusOfSMS($messageId)
    {
        $uri = '/v1/message/status/'.$messageId;

        $body = [
            'auth'    => [$this->key, $this->secret],
            'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')],
        ];

        try {
            $response = $this->guzzleHTTPClient->get($uri, $body);
        } catch (ClientException $e) {
            throw $this->createAppropriateSinchException($e);
        }

        $result = null;

        if (Response::HTTP_OK === $response->getStatusCode() && $response->hasHeader('Content-Type') &&
            'application/json; charset=utf-8' === $response->getHeaderLine('Content-Type')
        ) {
            $content = $response->getBody()->getContents();
            $result = json_decode($content, true);
        };

        return $result;
    }

    /**
     * Create appropriate Sinch exception
     *
     * @param ClientException $e Exception
     *
     * @return \Exception|\Fresh\SinchBundle\Exception\SinchException
     */
    private function createAppropriateSinchException(ClientException $e)
    {
        $response = json_decode($e->getResponse()->getBody()->getContents(), true);
        $responseStatusCode = $e->getCode();

        $errorCode    = (int) $response['errorCode'];
        $errorMessage = $response['message'];

        $exception = null;

        switch ($responseStatusCode) {
            case Response::HTTP_BAD_REQUEST:
                $exception = $this->getSinchExceptionForBadRequest($errorCode, $errorMessage);
                break;
            case Response::HTTP_UNAUTHORIZED:
                $exception = $this->getSinchExceptionForUnauthorized($errorCode, $errorMessage);
                break;
            case Response::HTTP_PAYMENT_REQUIRED:
                $exception = $this->getSinchExceptionForPaymentRequired($errorCode, $errorMessage);
                break;
            case Response::HTTP_FORBIDDEN:
                $exception = $this->getSinchExceptionForForbidden($errorCode, $errorMessage);
                break;
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                $exception = $this->getSinchExceptionForInternalServerError($errorCode, $errorMessage);
                break;
        }

        if (null === $exception) {
            $exception = new \Exception('Unknown Sinch Error Code');
        }

        return $exception;
    }

    /**
     * Get Sinch exception for bad request
     *
     * @param int    $errorCode    Sinch error code
     * @param string $errorMessage Sinch error message
     *
     * @return \Fresh\SinchBundle\Exception\SinchException|null
     */
    private function getSinchExceptionForBadRequest($errorCode, $errorMessage)
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
     * Get Sinch exception for unauthorized
     *
     * @param int    $errorCode    Sinch error code
     * @param string $errorMessage Sinch error message
     *
     * @return \Fresh\SinchBundle\Exception\SinchException|null
     */
    private function getSinchExceptionForUnauthorized($errorCode, $errorMessage)
    {
        $exception = null;

        if (SinchErrorCode::ILLEGAL_AUTHORIZATION_HEADER === $errorCode) {
            $exception = new SinchIllegalAuthorizationHeaderException($errorMessage);
        }

        return $exception;
    }

    /**
     * Get Sinch exception for payment required
     *
     * Sinch returns 402 code when application run out of money
     *
     * @param int    $errorCode    Sinch error code
     * @param string $errorMessage Sinch error message
     *
     * @return \Fresh\SinchBundle\Exception\SinchException|null
     */
    private function getSinchExceptionForPaymentRequired($errorCode, $errorMessage)
    {
        $exception = null;

        if (SinchErrorCode::THERE_IS_NOT_ENOUGH_FUNDS_TO_SEND_THE_MESSAGE === $errorCode) {
            $exception = new SinchPaymentRequiredException($errorMessage);
        }

        return $exception;
    }

    /**
     * Get Sinch exception for forbidden
     *
     * @param int    $errorCode    Sinch error code
     * @param string $errorMessage Sinch error message
     *
     * @return \Fresh\SinchBundle\Exception\SinchException|null
     */
    private function getSinchExceptionForForbidden($errorCode, $errorMessage)
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
     * Get Sinch exception for internal server error
     *
     * @param int    $errorCode    Sinch error code
     * @param string $errorMessage Sinch error message
     *
     * @return \Fresh\SinchBundle\Exception\SinchException|null
     */
    private function getSinchExceptionForInternalServerError($errorCode, $errorMessage)
    {
        $exception = null;

        if (SinchErrorCode::INTERNAL_ERROR === $errorCode) {
            $exception = new SinchInternalErrorException($errorMessage);
        }

        return $exception;
    }

    // endregion
}
