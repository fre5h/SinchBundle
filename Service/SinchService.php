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

    /** @var Client */
    private $guzzleHTTPClient;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var string */
    private $host;

    /** @var string */
    private $key;

    /** @var string */
    private $secret;

    /** @var string */
    private $from;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string                   $host
     * @param string                   $key
     * @param string                   $secret
     * @param string|null              $from
     */
    public function __construct(EventDispatcherInterface $dispatcher, $host, $key, $secret, $from = null)
    {
        $this->dispatcher = $dispatcher;
        $this->host = $host;
        $this->key = $key;
        $this->secret = $secret;
        $this->from = $from;

        $this->guzzleHTTPClient = new Client([
            'base_uri' => rtrim($this->host, '/'),
        ]);
    }

    // region Public API

    /**
     * @param string      $phoneNumber
     * @param string      $messageText
     * @param string|null $from
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
            'auth' => [$this->key, $this->secret],
            'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')], // ISO 8601 date format
            'json' => ['message' => $messageText],
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

        if (Response::HTTP_OK === $response->getStatusCode() && $response->hasHeader('Content-Type')
            && 'application/json; charset=utf-8' === $response->getHeaderLine('Content-Type')
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
     * @param int $messageId
     *
     * @return string
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
     * @param int $messageId
     *
     * @return bool
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
     * @param int $messageId
     *
     * @return bool
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
     * @param int $messageId
     *
     * @return bool
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
     * @param int $messageId
     *
     * @return bool
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
     * @param int $messageId
     *
     * @return array|null
     *
     * @throws SinchException
     */
    private function sendRequestToCheckStatusOfSMS($messageId)
    {
        $uri = self::URL_FOR_CHECKING_SMS_STATUS.$messageId;

        $body = [
            'auth' => [$this->key, $this->secret],
            'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')],
        ];

        try {
            $response = $this->guzzleHTTPClient->get($uri, $body);
        } catch (ClientException $e) {
            throw SinchExceptionResolver::createAppropriateSinchException($e);
        }

        $result = null;

        if (Response::HTTP_OK === $response->getStatusCode() && $response->hasHeader('Content-Type')
            && 'application/json; charset=utf-8' === $response->getHeaderLine('Content-Type')
        ) {
            $content = $response->getBody()->getContents();
            $result = json_decode($content, true);
        };

        return $result;
    }

    // endregion
}
