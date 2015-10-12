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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

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
     * @param int    $phoneNumber Phone number
     * @param string $messageText Message text
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function sendSMS($phoneNumber, $messageText)
    {
        $uri = '/v1/sms/'.$phoneNumber;

        $response = $this->guzzleHTTPClient->post(
            $uri,
            [
                'auth' => [$this->key, $this->secret],
                'json' => ['message' => $messageText],
            ]
        );

        return $response;
    }

    /**
     * Get status of sent SMS
     *
     * @param int $messageId Message ID
     *
     * @return string SMS status (Successful, Unknown)
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
     * Returns true if SMS with some ID was sent successfully, false - otherwise
     *
     * @param int $messageId Message ID
     *
     * @return bool
     */
    public function smsIsSent($messageId)
    {
        $response = $this->sendRequestToCheckStatusOfSMS($messageId);

        $result = false;
        if (isset($response['status']) && 'Successful' === $response['status']) {
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
     */
    private function sendRequestToCheckStatusOfSMS($messageId)
    {
        $uri = '/v1/message/status/'.$messageId;

        $response = $this->guzzleHTTPClient->get($uri, ['auth' => [$this->key, $this->secret]]);

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
