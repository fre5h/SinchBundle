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

namespace Fresh\SinchBundle\Event;

/**
 * SinchEvents.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
final class SinchEvents
{
    /**
     * This event is triggered before the SMS is going to be sent.
     *
     * @see \Fresh\SinchBundle\Event\SmsEvent Listeners receive an instance of this class
     */
    public const PRE_SMS_SEND = 'sinch.sms.pre_send';

    /**
     * This event is triggered after the SMS is successfully sent.
     *
     * @see \Fresh\SinchBundle\Event\SmsEvent Listeners receive an instance of this class
     */
    public const POST_SMS_SEND = 'sinch.sms.post_send';

    /**
     * This event is triggered callback from Sinch is received.
     *
     * @see \Fresh\SinchBundle\Event\SmsMessageCallbackEvent Listeners receive an instance of this class
     */
    public const CALLBACK_RECEIVED = 'sinch.callback.received';
}
