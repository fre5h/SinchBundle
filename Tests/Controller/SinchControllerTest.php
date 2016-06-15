<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Controller;

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
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchControllerTest extends WebTestCase
{
    const DEFAULT_SINCH_CALLBACK_URL = '/sinch/callback';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TraceableEventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FormFactory
     */
    private $formFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Form
     */
    private $form;

    /**
     * @var SinchController
     */
    private $controller;

    protected function setUp()
    {
        $this->eventDispatcher = $this->getMockBuilder(TraceableEventDispatcher::class)
                                      ->disableOriginalConstructor()
                                      ->setMethods(['dispatch'])
                                      ->getMock();

        $this->formFactory = $this->getMockBuilder(FormFactory::class)
                                  ->disableOriginalConstructor()
                                  ->setMethods(['create'])
                                  ->getMock();

        $this->form = $this->getMockBuilder(Form::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['isValid', 'isSubmitted', 'handleRequest'])
                           ->getMock();

        $this->formFactory->expects($this->once())
                          ->method('create')
                          ->willReturn($this->form);

        $this->controller = new SinchController($this->formFactory, $this->eventDispatcher);
    }

    protected function tearDown()
    {
        unset($this->formFactory);
        unset($this->eventDispatcher);
        unset($this->controller);
        unset($this->form);
    }

    public function testValidCallback()
    {
        $this->form->expects($this->once())
                   ->method('isSubmitted')
                   ->willReturn(true);

        $this->form->expects($this->once())
                   ->method('isValid')
                   ->willReturn(true);

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testNotSubmittedData()
    {
        $this->form->expects($this->once())
                   ->method('isSubmitted')
                   ->willReturn(false);
        $this->form->expects($this->never())
                   ->method('isValid');

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testNotValidData()
    {
        $this->form->expects($this->once())
                   ->method('isSubmitted')
                   ->willReturn(true);

        $this->form->expects($this->once())
                   ->method('isValid')
                   ->willReturn(false);

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testInternalError()
    {
        $this->eventDispatcher->expects($this->once())
                              ->method('dispatch')
                              ->willThrowException(new \Exception());

        $this->form->expects($this->once())
                   ->method('isSubmitted')
                   ->willReturn(true);

        $this->form->expects($this->once())
                   ->method('isValid')
                   ->willReturn(true);

        $request = Request::create(self::DEFAULT_SINCH_CALLBACK_URL, 'POST');
        $response = $this->controller->callbackAction($request);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
}
