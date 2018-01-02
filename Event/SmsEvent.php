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

use Symfony\Component\EventDispatcher\Event;

/**
 * SmsEvent.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SmsEvent extends Event
{
    /** @var string */
    private $number;

    /** @var string */
    private $message;

    /** @var string|null */
    private $from;

    /**
     * @param string      $number
     * @param string      $message
     * @param string|null $from
     */
    public function __construct(string $number, string $message, ?string $from = null)
    {
        $this->number = $number;
        $this->message = $message;
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return $this
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
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
     * @return null|string
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @param null|string $from
     *
     * @return $this
     */
    public function setFrom(?string $from): self
    {
        $this->from = $from;

        return $this;
    }
}
