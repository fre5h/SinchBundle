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

use Fresh\SinchBundle\Model\CallbackRequest;
use Fresh\SinchBundle\Model\Identity;
use Symfony\Component\EventDispatcher\Event;

/**
 * SmsMessageCallbackEvent.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SmsMessageCallbackEvent extends Event
{
    /** @var CallbackRequest */
    private $callbackRequest;

    /**
     * @param CallbackRequest $callbackRequest
     */
    public function __construct(CallbackRequest $callbackRequest)
    {
        $this->callbackRequest = $callbackRequest;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->callbackRequest->getEvent();
    }

    /**
     * @return Identity
     */
    public function getTo(): Identity
    {
        return $this->callbackRequest->getTo();
    }

    /**
     * @return Identity
     */
    public function getFrom(): Identity
    {
        return $this->callbackRequest->getFrom();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->callbackRequest->getMessage();
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->callbackRequest->getTimestamp();
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->callbackRequest->getVersion();
    }
}
