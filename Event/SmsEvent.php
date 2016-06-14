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

/**
 * SmsEvent.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SmsEvent
{
    /**
     * @var string $number Number
     */
    private $number;

    /**
     * @var string $message Message
     */
    private $message;

    /**
     * @var string|null $from From
     */
    private $from;

    /**
     * Constructor.
     *
     * @param string      $number  Number
     * @param string      $message Message
     * @param string|null $from    From
     */
    public function __construct($number, $message, $from = null)
    {
        $this->number  = $number;
        $this->message = $message;
        $this->from    = $from;
    }

    /**
     * Get number.
     *
     * @return string Number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number.
     *
     * @param string $number Number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

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
     * Get from.
     *
     * @return null|string From
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set from.
     *
     * @param null|string $from From
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }
}
