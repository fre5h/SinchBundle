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
 * Identity Model.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
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
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     *
     * @return $this
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}
