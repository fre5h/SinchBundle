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

namespace Fresh\SinchBundle\Helper;

/**
 * Sinch SMS Status.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
final class SinchSmsStatus
{
    public const PENDING = 'pending';

    public const SUCCESSFUL = 'successful';

    public const FAULTED = 'faulted';

    public const UNKNOWN = 'unknown';

    /**
     * Constructor.
     */
    private function __construct()
    {
    }
}
