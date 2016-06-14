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
 * @link https://www.sinch.com/docs/sms/#incomingsms
 */
class Identity
{
    /**
     * @var string $type Type
     *
     * @Assert\NotNull(message="Type cannot be null.")
     * @Assert\Type(type="string")
     */
    private $type;

    /**
     * @var string $endpoint Endpoint
     *
     * @Assert\NotNull(message="Endpoint cannot be null.")
     * @Assert\Type(type="string")
     */
    private $endpoint;

    /**
     * Get type.
     *
     * @return string Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get endpoint.
     *
     * @return string Endpoint
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set endpoint.
     *
     * @param string $endpoint Endpoint
     *
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}
