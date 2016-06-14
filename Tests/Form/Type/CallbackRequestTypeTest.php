<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Form\Type;

use Fresh\SinchBundle\Form\Type\CallbackRequestType;
use Fresh\SinchBundle\Model\CallbackRequest;
use Fresh\SinchBundle\Model\Identity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * CallbackRequestTypeTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class CallbackRequestTypeTest extends TypeTestCase
{
    public function testGetBlockPrefix()
    {
        $this->assertNull((new CallbackRequestType)->getBlockPrefix());
    }

    public function testFormBuilder()
    {
        $form = $this->factory->createBuilder(CallbackRequestType::class)->getForm();

        $this->assertEquals(6, $form->count());
        $this->assertInstanceOf(FormInterface::class, $form->get('event'));
        $this->assertInstanceOf(FormInterface::class, $form->get('from'));
        $this->assertInstanceOf(FormInterface::class, $form->get('to'));
        $this->assertInstanceOf(FormInterface::class, $form->get('message'));
        $this->assertInstanceOf(FormInterface::class, $form->get('timestamp'));
        $this->assertInstanceOf(FormInterface::class, $form->get('version'));
    }

    public function testGetDefaultOptions()
    {
        $type = new CallbackRequestType();

        $optionResolver = new OptionsResolver();

        $type->setDefaultOptions($optionResolver);

        $options = $optionResolver->resolve();

        $this->assertFalse($options['csrf_protection']);
        $this->assertEquals(CallbackRequest::class, $options['data_class']);
    }

    public function testSubmitValidData()
    {
        $data = [
            'event'     => 'incomingSms',
            'to'        => [
                'type'     => 'number',
                'endpoint' => '+46700000001',
            ],
            'from'      => [
                'type'     => 'number',
                'endpoint' => '+46700000000',
            ],
            'message'   => 'Hello world',
            'timestamp' => '2014-12-01T12:00:00Z',
            'version'   => 1,
        ];

        $form = $this->factory->create(CallbackRequestType::class);

        $identity = (new CallbackRequest())
            ->setEvent('incomingSms')
            ->setFrom((new Identity())->setType('number')->setEndpoint('+46700000000'))
            ->setTo((new Identity())->setType('number')->setEndpoint('+46700000001'))
            ->setMessage('Hello world')
            ->setTimestamp(new \DateTime('2014-12-01T12:00:00Z'))
            ->setVersion(1);

        // Submit the data to the form directly
        $form->submit($data);

        $this->assertTrue($form->isSynchronized());

        /** @var \Fresh\SinchBundle\Model\CallbackRequest $formData */
        $formData = $form->getData();
        $this->assertEquals($identity, $formData);
        $this->assertInternalType('string', $formData->getEvent());
        $this->assertInternalType('object', $formData->getFrom());
        $this->assertInternalType('object', $formData->getTo());
        $this->assertInternalType('string', $formData->getMessage());
        $this->assertInternalType('object', $formData->getTimestamp());
        $this->assertInternalType('integer', $formData->getVersion());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
