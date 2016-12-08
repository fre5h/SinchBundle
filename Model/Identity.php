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
 * Identity Model.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 *
 * @see https://www.sinch.com/docs/sms/#incomingsms
 */
class Identity
{
    /**
     * @var string
     *
     * @Assert\NotNull(message="Type cannot be null.")
     * @Assert\Type(type="string")
     */
    private $type;

    /**
     * @var string
     *
     * @Assert\NotNull(message="Endpoint cannot be null.")
     * @Assert\Type(type="string")
     */
    private $endpoint;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     *
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}
