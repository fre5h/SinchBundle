<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
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
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class CallbackRequestTypeTest extends AbstractTypeTestCase
{
    public function testGetBlockPrefix(): void
    {
        self::assertEmpty((new CallbackRequestType)->getBlockPrefix());
    }

    public function testFormBuilder(): void
    {
        $form = $this->factory->createBuilder(CallbackRequestType::class)->getForm();

        self::assertEquals(6, $form->count());
        self::assertInstanceOf(FormInterface::class, $form->get('event'));
        self::assertInstanceOf(FormInterface::class, $form->get('from'));
        self::assertInstanceOf(FormInterface::class, $form->get('to'));
        self::assertInstanceOf(FormInterface::class, $form->get('message'));
        self::assertInstanceOf(FormInterface::class, $form->get('timestamp'));
        self::assertInstanceOf(FormInterface::class, $form->get('version'));
    }

    public function testGetDefaultOptions(): void
    {
        $type = new CallbackRequestType();

        $optionResolver = new OptionsResolver();

        $type->configureOptions($optionResolver);

        $options = $optionResolver->resolve();

        self::assertFalse($options['csrf_protection']);
        self::assertEquals(CallbackRequest::class, $options['data_class']);
    }

    public function testSubmitValidData(): void
    {
        $data = [
            'event' => 'incomingSms',
            'to' => [
                'type' => 'number',
                'endpoint' => '+46700000001',
            ],
            'from' => [
                'type' => 'number',
                'endpoint' => '+46700000000',
            ],
            'message' => 'Hello world',
            'timestamp' => '2014-12-01T12:00:00Z',
            'version' => 1,
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

        self::assertTrue($form->isSynchronized());

        /** @var CallbackRequest $formData */
        $formData = $form->getData();
        self::assertEquals($identity, $formData);
        self::assertIsString($formData->getEvent());
        self::assertIsObject($formData->getFrom());
        self::assertIsObject($formData->getTo());
        self::assertIsString($formData->getMessage());
        self::assertIsObject($formData->getTimestamp());
        self::assertIsInt($formData->getVersion());

        $view = $form->createView();
        $children = $view->children;

        foreach (\array_keys($data) as $key) {
            self::assertArrayHasKey($key, $children);
        }
    }
}
