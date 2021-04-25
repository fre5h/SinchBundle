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

use Fresh\SinchBundle\Event\PostSmsSendEvent;
use Fresh\SinchBundle\Event\PreSmsSendEvent;
use Fresh\SinchBundle\Exception\SinchException;
use Fresh\SinchBundle\Helper\SinchSmsStatus;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Sinch.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class Sinch
{
    public const URL_FOR_SENDING_SMS = '/v1/sms/';

    public const URL_FOR_CHECKING_SMS_STATUS = '/v1/message/status/';

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var HttpClientInterface */
    private $httpClient;

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
     * @param HttpClientInterface      $httpClient
     * @param string                   $host
     * @param string                   $key
     * @param string                   $secret
     * @param string|null              $from
     */
    public function __construct(EventDispatcherInterface $dispatcher, HttpClientInterface $httpClient, $host, $key, $secret, $from = null)
    {
        $this->dispatcher = $dispatcher;
        $this->httpClient = $httpClient;
        $this->host = $host;
        $this->key = $key;
        $this->secret = $secret;
        $this->from = $from;
    }

    /**
     * @param string      $phoneNumber
     * @param string      $messageText
     * @param string|null $from
     *
     * @return int Message ID
     *
     * @throws SinchException
     */
    public function sendSMS(string $phoneNumber, string $messageText, ?string $from = null): ?int
    {
        // @todo validate phone number

        $body = [
            'message' => $messageText,
        ];

        if (null !== $from) {
            $body['from'] = $from;
        } elseif (null !== $this->from) {
            $from = $this->from;
            $body['from'] = $from;
        }

        $this->dispatcher->dispatch(new PreSmsSendEvent($phoneNumber, $messageText, $from));

        $response = $this->httpClient->request(
            Request::METHOD_POST,
            self::URL_FOR_SENDING_SMS.$phoneNumber,
            [
                'auth_basic' => [$this->key, $this->secret],
                'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')],
                'json' => $body,
            ]
        );

        $this->dispatcher->dispatch(new PostSmsSendEvent($phoneNumber, $messageText, $from));

        $messageId = null;

        $headers = $response->getHeaders();
        if (Response::HTTP_OK === $response->getStatusCode() && isset($headers['Content-Type'])
            && 'application/json; charset=utf-8' === $headers['Content-Type']
        ) {
            $content = \json_decode($response->getContent(), true);
            if (\array_key_exists('messageId', $content)) {
                $messageId = $content['messageId'];
            }
        }

        return $messageId;
    }

    /**
     * @param int $messageId
     *
     * @return string
     */
    public function getStatusOfSMS(int $messageId): string
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);
        $result = '';

        if (\array_key_exists('status', $response)) {
            $result = $response['status'];
        }

        return $result;
    }

    /**
     * @param int $messageId
     *
     * @return bool
     */
    public function smsIsSentSuccessfully(int $messageId): bool
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (\array_key_exists('status', $response) && SinchSmsStatus::SUCCESSFUL === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param int $messageId
     *
     * @return bool
     */
    public function smsIsPending(int $messageId): bool
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (\array_key_exists('status', $response) && SinchSmsStatus::PENDING === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param int $messageId
     *
     * @return bool
     */
    public function smsIsFaulted(int $messageId): bool
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (\array_key_exists('status', $response) && SinchSmsStatus::FAULTED === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param int $messageId
     *
     * @return bool
     */
    public function smsInUnknownStatus(int $messageId): bool
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (\array_key_exists('status', $response) && SinchSmsStatus::UNKNOWN === $response['status']) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param int $messageId
     *
     * @return array|null
     */
    private function sendRequestToCheckStatusOfSMS(int $messageId): ?array
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            self::URL_FOR_CHECKING_SMS_STATUS.$messageId,
            [
                'auth_basic' => [$this->key, $this->secret],
                'headers' => ['X-Timestamp' => (new \DateTime('now'))->format('c')],
            ]
        );

        $result = null;

        $headers = $response->getHeaders();
        if (Response::HTTP_OK === $response->getStatusCode() && isset($headers['Content-Type'])
            && 'application/json; charset=utf-8' === $headers['Content-Type']
        ) {
            $result = \json_decode($response->getContent(), true);
        }

        return $result;
    }
}
