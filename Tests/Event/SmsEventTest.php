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

use Fresh\SinchBundle\Event\SmsEvent;

/**
 * SmsEventTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 *
 * @see \Fresh\SinchBundle\Event\SmsEvent
 */
class SmsEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithoutFrom()
    {
        $number  = '+46700000000';
        $message = 'Hello world';

        $smsEvent = new SmsEvent($number, $message);

        $this->assertEquals($number, $smsEvent->getNumber());
        $this->assertEquals($message, $smsEvent->getMessage());
    }

    public function testConstructorWithFrom()
    {
        $number  = '+46700000000';
        $message = 'Hello world';
        $from    = 'Santa Claus';

        $smsEvent = new SmsEvent($number, $message, $from);

        $this->assertEquals($number, $smsEvent->getNumber());
        $this->assertEquals($message, $smsEvent->getMessage());
        $this->assertEquals($from, $smsEvent->getFrom());
    }

    public function testSetGetNumber()
    {
        $smsEvent = new SmsEvent('', '');

        $number  = '+46700000000';
        $smsEvent->setNumber($number);
        $this->assertEquals($number, $smsEvent->getNumber());
    }

    public function testSetGetMessage()
    {
        $smsEvent = new SmsEvent('', '');

        $message  = 'Hello world';
        $smsEvent->setMessage($message);
        $this->assertEquals($message, $smsEvent->getMessage());
    }

    public function testSetGetFrom()
    {
        $smsEvent = new SmsEvent('', '');

        $from    = 'Santa Claus';
        $smsEvent->setFrom($from);
        $this->assertEquals($from, $smsEvent->getFrom());
    }
}
