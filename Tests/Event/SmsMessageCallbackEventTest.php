<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Event;

use Fresh\SinchBundle\Event\SmsMessageCallbackEvent;
use Fresh\SinchBundle\Model\CallbackRequest;
use Fresh\SinchBundle\Model\Identity;
use PHPUnit\Framework\TestCase;

/**
 * SmsMessageCallbackEventTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SmsMessageCallbackEventTest extends TestCase
{
    public function testConstructor(): void
    {
        $event = 'incomingSms';
        $from = new Identity();
        $to = new Identity();
        $message = 'Hello world';
        $timestamp = new \DateTime('now');
        $version = 1;

        $callbackRequest = (new CallbackRequest())
            ->setEvent($event)
            ->setFrom($from)
            ->setTo($to)
            ->setMessage($message)
            ->setTimestamp($timestamp)
            ->setVersion($version);

        $callbackEvent = new SmsMessageCallbackEvent($callbackRequest);

        self::assertEquals($event, $callbackEvent->getEvent());
        self::assertEquals($from, $callbackEvent->getFrom());
        self::assertEquals($to, $callbackEvent->getTo());
        self::assertEquals($message, $callbackEvent->getMessage());
        self::assertEquals($timestamp, $callbackEvent->getTimestamp());
        self::assertEquals($version, $callbackEvent->getVersion());
    }
}
