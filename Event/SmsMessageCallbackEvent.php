<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Event;

use Fresh\SinchBundle\Model\CallbackRequest;
use Fresh\SinchBundle\Model\Identity;
use Symfony\Component\EventDispatcher\Event;

/**
 * SmsMessageCallbackEvent.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SmsMessageCallbackEvent extends Event
{
    /**
     * @var CallbackRequest $callbackRequest Callback request
     */
    private $callbackRequest;

    /**
     * Constructor.
     *
     * @param CallbackRequest $callbackRequest Callback request
     */
    public function __construct(CallbackRequest $callbackRequest)
    {
        $this->callbackRequest = $callbackRequest;
    }

    /**
     * Get event.
     *
     * @return string Event
     */
    public function getEvent()
    {
        return $this->callbackRequest->getEvent();
    }

    /**
     * Get to.
     *
     * @return Identity To
     */
    public function getTo()
    {
        return $this->callbackRequest->getTo();
    }

    /**
     * Get from.
     *
     * @return Identity From
     */
    public function getFrom()
    {
        return $this->callbackRequest->getFrom();
    }

    /**
     * Get message.
     *
     * @return string Message
     */
    public function getMessage()
    {
        return $this->callbackRequest->getMessage();
    }

    /**
     * Get timestamp.
     *
     * @return \DateTime Timestamp
     */
    public function getTimestamp()
    {
        return $this->callbackRequest->getTimestamp();
    }

    /**
     * Get version.
     *
     * @return int Version
     */
    public function getVersion()
    {
        return $this->callbackRequest->getVersion();
    }
}
