<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Model;

/**
 * CallbackRequest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 *
 * @link https://www.sinch.com/docs/sms/#incomingsms
 */
class CallbackRequest
{
    /**
     * @var string $event Event
     */
    private $event;

    /**
     * @var Identity $to To
     */
    private $to;

    /**
     * @var Identity $from From
     */
    private $from;

    /**
     * @var string $message Message
     */
    private $message;

    /**
     * @var \DateTime $timestamp Timestamp
     */
    private $timestamp;

    /**
     * @var int $version Version
     */
    private $version;

    /**
     * Get event.
     *
     * @return string Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set event.
     *
     * @param string $event Event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get to.
     *
     * @return Identity To
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set to.
     *
     * @param Identity $to To
     *
     * @return $this
     */
    public function setTo(Identity $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get from.
     *
     * @return Identity From
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set from.
     *
     * @param Identity $from From
     *
     * @return $this
     */
    public function setFrom(Identity $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message.
     *
     * @param string $message Message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get timestamp.
     *
     * @return \DateTime Timestamp
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set timestamp.
     *
     * @param \DateTime $timestamp Timestamp
     *
     * @return $this
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get version.
     *
     * @return int Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set version.
     *
     * @param int $version Version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }
}
