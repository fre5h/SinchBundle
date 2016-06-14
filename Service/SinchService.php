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

use Fresh\SinchBundle\Event\SinchEvents;
use Fresh\SinchBundle\Event\SmsEvent;
use Fresh\SinchBundle\Exception\SinchException;
use Fresh\SinchBundle\Helper\SinchSmsStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * SinchService.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchService
{
    const URL_FOR_SENDING_SMS = '/v1/sms/';

    const URL_FOR_CHECKING_SMS_STATUS = '/v1/message/status/';

    /**
     * @var Client $guzzleHTTPClient Guzzle HTTP client
     */
    private $guzzleHTTPClient;

    /**
     * @var EventDispatcherInterface $dispatcher Event dispatcher
     */
    private $dispatcher;

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
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher Dispatcher
     * @param string                   $host       Host
     * @param string                   $key        Key
     * @param string                   $secret     Secret
     * @param string|null              $from       From
     */
    public function __construct(EventDispatcherInterface $dispatcher, $host, $key, $secret, $from = null)
    {
        $this->dispatcher = $dispatcher;
        $this->host       = $host;
        $this->key        = $key;
        $this->secret     = $secret;
        $this->from       = $from;

        $this->guzzleHTTPClient = new Client([
            'base_uri' => rtrim($this->host, '/'),
        ]);
    }

    // region Public API

    /**
     * Send SMS.
     *
     * @param string      $phoneNumber Phone number
     * @param string      $messageText Message text
     * @param string|null $from        From
     *
     * @return int Message ID
     *
     * @throws SinchException
     * @throws GuzzleException
     */
    public function sendSMS($phoneNumber, $messageText, $from = null)
    {
        $uri = self::URL_FOR_SENDING_SMS.$phoneNumber; // @todo validate phone number

        $body = [
            'auth'    => [$this->key, $this->secret],
            'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')], // ISO 8601 date format
            'json'    => ['message' => $messageText],
        ];

        if (null !== $from) {
            $body['json']['from'] = $from;
        } elseif (null !== $this->from) {
            $from = $this->from;
            $body['json']['from'] = $from;
        }

        try {
            $smsEvent = new SmsEvent($phoneNumber, $messageText, $from);

            $this->dispatcher->dispatch(SinchEvents::PRE_SMS_SEND, $smsEvent);
            $response = $this->guzzleHTTPClient->post($uri, $body);
            $this->dispatcher->dispatch(SinchEvents::POST_SMS_SEND, $smsEvent);
        } catch (ClientException $e) {
            throw SinchExceptionResolver::createAppropriateSinchException($e);
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
     * Get status of sent SMS.
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

    // region Check status helpers

    /**
     * Returns true if SMS with some ID was sent successfully, otherwise returns false.
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS was sent successfully, otherwise - false
     */
    public function smsIsSentSuccessfully($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SinchSmsStatus::SUCCESSFUL === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns true if SMS with some ID is still pending, otherwise returns false.
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS is still pending, otherwise - false
     */
    public function smsIsPending($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SinchSmsStatus::PENDING === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns true if SMS with some ID was faulted, otherwise returns false.
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS was faulted, otherwise - false
     */
    public function smsIsFaulted($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SinchSmsStatus::FAULTED === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns true if SMS with some ID in unknown status, otherwise returns false.
     *
     * @param int $messageId Message ID
     *
     * @return bool True if SMS in unknown status, otherwise - false
     */
    public function smsInUnknownStatus($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && SinchSmsStatus::UNKNOWN === $response['status']) {
            $result = true;
        }

        return $result;
    }

    // endregion

    // region Private functions

    /**
     * Send request to check status of SMS.
     *
     * @param int $messageId Message ID
     *
     * @return array|null
     *
     * @throws SinchException
     */
    private function sendRequestToCheckStatusOfSMS($messageId)
    {
        $uri = self::URL_FOR_CHECKING_SMS_STATUS.$messageId;

        $body = [
            'auth'    => [$this->key, $this->secret],
            'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')],
        ];

        try {
            $response = $this->guzzleHTTPClient->get($uri, $body);
        } catch (ClientException $e) {
            throw SinchExceptionResolver::createAppropriateSinchException($e);
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

    // endregion
}
