<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Exception;

/**
 * SinchPaymentRequiredException
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchPaymentRequiredException extends \RuntimeException
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'SMS was not sent. Looks like your Sinch account run out of money';
}
