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

use Fresh\SinchBundle\Event\CallbackEvent;
use Fresh\SinchBundle\Model\CallbackRequest;

/**
 * CallbackEventTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 *
 * @see \Fresh\SinchBundle\Event\CallbackEvent
 */
class CallbackEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $callbackRequest = new CallbackRequest();
        $callbackEvent = new CallbackEvent($callbackRequest);
        $this->assertEquals($callbackRequest, $callbackEvent->getCallbackRequest());
    }
}
