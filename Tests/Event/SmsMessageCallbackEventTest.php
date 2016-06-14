<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Event;

use Fresh\SinchBundle\Event\SmsMessageCallbackEvent;
use Fresh\SinchBundle\Model\CallbackRequest;
use Fresh\SinchBundle\Model\Identity;

/**
 * SmsMessageCallbackEventTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SmsMessageCallbackEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $event     = 'incomingSms';
        $from      = new Identity();
        $to        = new Identity();
        $message   = 'Hello world';
        $timestamp = new \DateTime('now');
        $version   = 1;

        $callbackRequest = (new CallbackRequest())
            ->setEvent($event)
            ->setFrom($from)
            ->setTo($to)
            ->setMessage($message)
            ->setTimestamp($timestamp)
            ->setVersion($version);
        $callbackEvent = new SmsMessageCallbackEvent($callbackRequest);

        $this->assertEquals($event, $callbackEvent->getEvent());
        $this->assertEquals($from, $callbackEvent->getFrom());
        $this->assertEquals($to, $callbackEvent->getTo());
        $this->assertEquals($message, $callbackEvent->getMessage());
        $this->assertEquals($timestamp, $callbackEvent->getTimestamp());
        $this->assertEquals($version, $callbackEvent->getVersion());
    }
}
