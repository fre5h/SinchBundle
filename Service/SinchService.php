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

use Fresh\SinchBundle\Exception\SinchPaymentRequiredException;
use Fresh\SinchBundle\Sms\SmsStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

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
     * Constructor
     *
     * @param string $host   Host
     * @param string $key    Key
     * @param string $secret Secret
     */
    public function __construct($host, $key, $secret)
    {
        $this->host   = $host;
        $this->key    = $key;
        $this->secret = $secret;

        $this->guzzleHTTPClient = new Client([
            'base_uri' => rtrim($this->host, '/'),
        ]);
    }

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
     * @throws SinchPaymentRequiredException When run out of money
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
        }

        try {
            $response = $this->guzzleHTTPClient->post($uri, $body);
        } catch (ClientException $e) {
            // Sinch return 402 code when application run out of money
            if (402 === $e->getCode()) {
                throw new SinchPaymentRequiredException();
            }
        }

        $messageId = null;
        if (200 === $response->getStatusCode() && $response->hasHeader('Content-Type') &&
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

    /**
     * Returns true if SMS with some ID was sent successfully, otherwise returns false
     *
     * @param int $messageId Message ID
     *
     * @return bool True if message is sent, otherwise - false
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

        $response = $this->guzzleHTTPClient->get($uri, $body);

        try {
            $response = $this->guzzleHTTPClient->get($uri, $body);
        } catch (ClientException $e) {
            // Sinch return 402 code when application run out of money
            if (402 === $e->getCode()) {
                throw new SinchPaymentRequiredException();
            }
        }

        $result = null;
        if (200 === $response->getStatusCode() && $response->hasHeader('Content-Type') &&
            'application/json; charset=utf-8' === $response->getHeaderLine('Content-Type')
        ) {
            $content = $response->getBody()->getContents();
            $result = json_decode($content, true);
        };

        return $result;
    }
}
