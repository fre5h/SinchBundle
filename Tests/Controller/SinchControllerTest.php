<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Fresh\SinchBundle\Controller\SinchController;

/**
 * SinchControllerTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SinchControllerTest extends WebTestCase
{
    public const DEFAULT_SINCH_CALLBACK_URL = '/sinch/callback';

    /** @var TraceableEventDispatcher|MockObject */
    private $eventDispatcher;

    /** @var FormFactory|MockObject */
    private $formFactory;

    /** @var Form|MockObject */
    private $form;

    /** @var SinchController */
    private $controller;

    protected function setUp(): void
    {
        $this->eventDispatcher = $this->createMock(TraceableEventDispatcher::class);
        $this->form = $this->createMock(Form::class);
        $this->formFactory = $this->createMock(FormFactory::class);
        $this->formFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($this->form)
        ;

        $this->controller = new SinchController($this->formFactory, $this->eventDispatcher);
    }

    protected function tearDown(): void
    {
        unset(
            $this->eventDispatcher,
            $this->form,
            $this->formFactory,
            $this->controller,
        );
    }

    public function testValidCallback(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true)
        ;

        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testNotSubmittedData(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(false)
        ;
        $this->form
            ->expects($this->never())
            ->method('isValid')
        ;

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testNotValidData(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true)
        ;

        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(false)
        ;

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testInternalError(): void
    {
        $this->eventDispatcher
            ->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new \Exception())
        ;

        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true)
        ;

        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
}
