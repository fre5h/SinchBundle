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

/**
 * HTTPClientInterface.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class HTTPClientService implements HTTPClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function post($uri, $body = [], $headers = [])
    {
        // @todo
    }

    /**
     * {@inheritdoc}
     */
    public function get($uri, $queryParameters = [], $headers = [])
    {
        // @todo
    }
}
