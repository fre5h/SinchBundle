<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Model;

use Fresh\SinchBundle\Model\CallbackRequest;
use Fresh\SinchBundle\Model\Identity;
use PHPUnit\Framework\TestCase;

/**
 * CallbackRequestTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class CallbackRequestTest extends TestCase
{
    public function testConstructor(): void
    {
        $callbackRequest = new CallbackRequest();

        self::assertNull($callbackRequest->getEvent());
        self::assertNull($callbackRequest->getFrom());
        self::assertNull($callbackRequest->getTo());
        self::assertNull($callbackRequest->getMessage());
        self::assertNull($callbackRequest->getTimestamp());
        self::assertNull($callbackRequest->getVersion());
    }

    public function testSetGetEvent(): void
    {
        $event = 'incomingSms';
        $callbackRequest = (new CallbackRequest())->setEvent($event);
        self::assertEquals($event, $callbackRequest->getEvent());
    }

    public function testSetGetTo(): void
    {
        $to = new Identity();
        $callbackRequest = (new CallbackRequest())->setTo($to);
        self::assertEquals($to, $callbackRequest->getTo());
    }

    public function testSetGetFrom(): void
    {
        $from = new Identity();
        $callbackRequest = (new CallbackRequest())->setFrom($from);
        self::assertEquals($from, $callbackRequest->getFrom());
    }

    public function testSetGetMessage(): void
    {
        $message = 'Hello world';
        $callbackRequest = (new CallbackRequest())->setMessage($message);
        self::assertEquals($message, $callbackRequest->getMessage());
    }

    public function testSetGetTimestamp(): void
    {
        $timestamp = new \DateTime('now');
        $callbackRequest = (new CallbackRequest())->setTimestamp($timestamp);
        self::assertEquals($timestamp, $callbackRequest->getTimestamp());
    }

    public function testSetGetVersion(): void
    {
        $version = 1;
        $callbackRequest = (new CallbackRequest())->setVersion($version);
        self::assertEquals($version, $callbackRequest->getVersion());
    }
}
