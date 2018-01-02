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

namespace Fresh\SinchBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * CallbackRequest Model.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 *
 * @see https://www.sinch.com/docs/sms/#incomingsms
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
     * @return string|null
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    public function setEvent(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Identity
     */
    public function getTo(): ?Identity
    {
        return $this->to;
    }

    /**
     * @param Identity $to
     *
     * @return $this
     */
    public function setTo(Identity $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return Identity
     */
    public function getFrom(): ?Identity
    {
        return $this->from;
    }

    /**
     * @param Identity $from
     *
     * @return $this
     */
    public function setFrom(Identity $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     *
     * @return $this
     */
    public function setTimestamp(\DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return $this
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }
}
