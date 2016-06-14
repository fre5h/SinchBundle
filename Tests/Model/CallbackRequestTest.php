<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Model;

use Fresh\SinchBundle\Model\CallbackRequest;
use Fresh\SinchBundle\Model\Identity;

/**
 * CallbackRequestTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 *
 * @see \Fresh\SinchBundle\Model\CallbackRequest
 */
class CallbackRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $callbackRequest = new CallbackRequest();

        $this->assertNull($callbackRequest->getEvent());
        $this->assertNull($callbackRequest->getFrom());
        $this->assertNull($callbackRequest->getTo());
        $this->assertNull($callbackRequest->getMessage());
        $this->assertNull($callbackRequest->getTimestamp());
        $this->assertNull($callbackRequest->getVersion());
    }

    public function testSetGetEvent()
    {
        $event = 'incomingSms';
        $callbackRequest = (new CallbackRequest())->setEvent($event);
        $this->assertEquals($event, $callbackRequest->getEvent());
    }

    public function testSetGetTo()
    {
        $to = new Identity();
        $callbackRequest = (new CallbackRequest())->setTo($to);
        $this->assertEquals($to, $callbackRequest->getTo());
    }

    public function testSetGetFrom()
    {
        $from = new Identity();
        $callbackRequest = (new CallbackRequest())->setFrom($from);
        $this->assertEquals($from, $callbackRequest->getFrom());
    }

    public function testSetGetMessage()
    {
        $message = 'Hello world';
        $callbackRequest = (new CallbackRequest())->setMessage($message);
        $this->assertEquals($message, $callbackRequest->getMessage());
    }

    public function testSetGetTimestamp()
    {
        $timestamp = new \DateTime('now');
        $callbackRequest = (new CallbackRequest())->setTimestamp($timestamp);
        $this->assertEquals($timestamp, $callbackRequest->getTimestamp());
    }

    public function testSetGetVersion()
    {
        $version = 1;
        $callbackRequest = (new CallbackRequest())->setVersion($version);
        $this->assertEquals($version, $callbackRequest->getVersion());
    }
}
