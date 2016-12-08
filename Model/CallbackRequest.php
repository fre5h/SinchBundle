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

use Symfony\Component\Validator\Constraints as Assert;

/**
 * CallbackRequest Model.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 *
 * @link https://www.sinch.com/docs/sms/#incomingsms
 */
class CallbackRequest
{
    /**
     * @var string
     *
     * @Assert\NotNull(message="Event cannot be null.")
     * @Assert\Choice(choices = {"incomingSms"}, message = "Invalid event.")
     */
    private $event;

    /**
     * @var Identity
     *
     * @Assert\NotNull(message="To cannot be null.")
     * @Assert\Type(type="object")
     * @Assert\Valid()
     */
    private $to;

    /**
     * @var Identity
     *
     * @Assert\NotNull(message="From cannot be null.")
     * @Assert\Type(type="object")
     * @Assert\Valid()
     */
    private $from;

    /**
     * @var string
     *
     * @Assert\NotNull(message="Message cannot be null.")
     * @Assert\Type(type="string")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @Assert\NotNull(message="Timestamp cannot be null.")
     * @Assert\DateTime()
     */
    private $timestamp;

    /**
     * @var int
     *
     * @Assert\NotNull(message="Version cannot be null.")
     * @Assert\Type(type="integer")
     */
    private $version;

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Identity
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param Identity $to
     *
     * @return $this
     */
    public function setTo(Identity $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return Identity
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param Identity $from
     *
     * @return $this
     */
    public function setFrom(Identity $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     *
     * @return $this
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }
}
