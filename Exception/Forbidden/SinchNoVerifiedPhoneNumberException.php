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

namespace Fresh\SinchBundle\Exception\Forbidden;

use Fresh\SinchBundle\Exception\SinchException;

/**
 * SinchNoVerifiedPhoneNumberException.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SinchNoVerifiedPhoneNumberException extends SinchException
{
}
