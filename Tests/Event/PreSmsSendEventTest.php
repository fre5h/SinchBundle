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

use Fresh\SinchBundle\Event\PreSmsSendEvent;
use PHPUnit\Framework\TestCase;

/**
 * PreSmsSendEventTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class PreSmsSendEventTest extends TestCase
{
    public function testConstructorWithoutFrom(): void
    {
        $number = '+46700000000';
        $message = 'Hello world';

        $preSmsSendEvent = new PreSmsSendEvent($number, $message);

        self::assertEquals($number, $preSmsSendEvent->getNumber());
        self::assertEquals($message, $preSmsSendEvent->getMessage());
    }

    public function testConstructorWithFrom(): void
    {
        $number = '+46700000000';
        $message = 'Hello world';
        $from = 'Santa Claus';

        $preSmsSendEvent = new PreSmsSendEvent($number, $message, $from);

        self::assertEquals($number, $preSmsSendEvent->getNumber());
        self::assertEquals($message, $preSmsSendEvent->getMessage());
        self::assertEquals($from, $preSmsSendEvent->getFrom());
    }

    public function testSetGetNumber(): void
    {
        $preSmsSendEvent = new PreSmsSendEvent('', '');

        $number = '+46700000000';
        $preSmsSendEvent->setNumber($number);
        self::assertEquals($number, $preSmsSendEvent->getNumber());
    }

    public function testSetGetMessage(): void
    {
        $preSmsSendEvent = new PreSmsSendEvent('', '');

        $message = 'Hello world';
        $preSmsSendEvent->setMessage($message);
        self::assertEquals($message, $preSmsSendEvent->getMessage());
    }

    public function testSetGetFrom(): void
    {
        $preSmsSendEvent = new PreSmsSendEvent('', '');

        $from = 'Santa Claus';
        $preSmsSendEvent->setFrom($from);
        self::assertEquals($from, $preSmsSendEvent->getFrom());
    }
}
