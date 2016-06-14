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

use AppBundle\Entity\Offer\Offer;
use Fresh\SinchBundle\Model\CallbackRequest;
use Symfony\Component\EventDispatcher\Event;

/**
 * CallbackEvent.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class CallbackEvent extends Event
{
    /**
     * @var CallbackRequest $callbackRequest Callback request
     */
    private $callbackRequest;

    /**
     * Constructor.
     *
     * @param Offer $callbackRequest Callback request
     */
    public function __construct(CallbackRequest $callbackRequest)
    {
        $this->callbackRequest = $callbackRequest;
    }

    /**
     * Get callback request.
     *
     * @return CallbackRequest
     */
    public function getCallbackRequest()
    {
        return $this->callbackRequest;
    }
}
