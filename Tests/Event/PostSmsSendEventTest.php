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

use Fresh\SinchBundle\Event\AbstractBaseSmsEvent;
use Fresh\SinchBundle\Event\PostSmsSendEvent;
use PHPUnit\Framework\TestCase;

/**
 * PostSmsSendEventTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class PostSmsSendEventTest extends TestCase
{
    public function testConstructorWithoutFrom(): void
    {
        $number = '+46700000000';
        $message = 'Hello world';

        $postSmsSendEvent = new PostSmsSendEvent($number, $message);

        self::assertEquals($number, $postSmsSendEvent->getNumber());
        self::assertEquals($message, $postSmsSendEvent->getMessage());
        self::assertInstanceOf(AbstractBaseSmsEvent::class, $postSmsSendEvent);
    }

    public function testConstructorWithFrom(): void
    {
        $number = '+46700000000';
        $message = 'Hello world';
        $from = 'Santa Claus';

        $postSmsSendEvent = new PostSmsSendEvent($number, $message, $from);

        self::assertEquals($number, $postSmsSendEvent->getNumber());
        self::assertEquals($message, $postSmsSendEvent->getMessage());
        self::assertEquals($from, $postSmsSendEvent->getFrom());
    }

    public function testSetGetNumber(): void
    {
        $postSmsSendEvent = new PostSmsSendEvent('', '');

        $number = '+46700000000';
        $postSmsSendEvent->setNumber($number);
        self::assertEquals($number, $postSmsSendEvent->getNumber());
    }

    public function testSetGetMessage(): void
    {
        $postSmsSendEvent = new PostSmsSendEvent('', '');

        $message = 'Hello world';
        $postSmsSendEvent->setMessage($message);
        self::assertEquals($message, $postSmsSendEvent->getMessage());
    }

    public function testSetGetFrom(): void
    {
        $postSmsSendEvent = new PostSmsSendEvent('', '');

        $from = 'Santa Claus';
        $postSmsSendEvent->setFrom($from);
        self::assertEquals($from, $postSmsSendEvent->getFrom());
    }
}
