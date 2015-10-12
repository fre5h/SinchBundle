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
}
