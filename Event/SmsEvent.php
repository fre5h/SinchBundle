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

use Symfony\Component\EventDispatcher\Event;

/**
 * SmsEvent.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
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
    public function __construct($number, $message, $from = null)
    {
        $this->number = $number;
        $this->message = $message;
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

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
     * @return null|string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param null|string $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }
}
