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
interface HTTPClientInterface
{
    /**
     * POST method
     *
     * @param string $uri     URI
     * @param array  $body    Body
     * @param array  $headers Headers
     *
     * @return mixed
     */
    public function post($uri, $body = [], $headers = []);

    /**
     * GET method
     *
     * @param string $uri             URI
     * @param array  $queryParameters Query parameters
     * @param array  $headers         Headers
     *
     * @return mixed
     */
    public function get($uri, $queryParameters = [], $headers = []);
}
