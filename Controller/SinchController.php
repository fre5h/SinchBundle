<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Controller;

use Fresh\SinchBundle\Event\SinchEvents;
use Fresh\SinchBundle\Event\SmsMessageCallbackEvent;
use Fresh\SinchBundle\Form\Type\CallbackRequestType;
use Fresh\SinchBundle\Model\CallbackRequest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SinchController.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchController
{
    /**
     * @var EventDispatcherInterface $eventDispatcher Event dispatcher
     */
    private $eventDispatcher;

    /**
     * @var FormFactory $formFactory Form factory
     */
    private $formFactory;

    /**
     * Constructor.
     *
     * @param FormFactory              $formFactory     Form factory
     * @param EventDispatcherInterface $eventDispatcher Event dispatcher
     */
    public function __construct(FormFactory $formFactory, EventDispatcherInterface $eventDispatcher)
    {
        $this->formFactory     = $formFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Callback action.
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function callbackAction(Request $request)
    {
        try {
            $callbackRequest = new CallbackRequest();
            $form = $this->formFactory->create(CallbackRequestType::class, $callbackRequest);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new SmsMessageCallbackEvent($callbackRequest);
                $this->eventDispatcher->dispatch(SinchEvents::CALLBACK_RECEIVED, $event);
            } else {
                return new Response('Bad Request', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new Response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(null, Response::HTTP_OK);
    }
}
