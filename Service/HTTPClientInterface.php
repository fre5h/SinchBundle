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

/**
 * HTTPClientInterface.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
interface HTTPClientInterface
{
    /**
     * @param string $uri
     * @param array  $body
     * @param array  $headers
     *
     * @return mixed
     */
    public function post(string $uri, array $body = [], array $headers = []);

    /**
     * @param string $uri
     * @param array  $queryParameters
     * @param array  $headers
     *
     * @return mixed
     */
    public function get(string $uri, array $queryParameters = [], array $headers = []);
}
