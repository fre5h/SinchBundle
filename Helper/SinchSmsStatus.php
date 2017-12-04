<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Helper;

/**
 * Sinch SMS Status.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
final class SinchSmsStatus
{
    const PENDING = 'pending';
    const SUCCESSFUL = 'successful';
    const FAULTED = 'faulted';
    const UNKNOWN = 'unknown';

    /**
     * Constructor.
     */
    private function __construct()
    {
    }
}
