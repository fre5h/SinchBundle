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

use Fresh\SinchBundle\Form\Type\IdentityType;
use Fresh\SinchBundle\Model\Identity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * IdentityTypeTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class IdentityTypeTest extends AbstractTypeTestCase
{
    public function testGetBlockPrefix(): void
    {
        self::assertEmpty((new IdentityType())->getBlockPrefix());
    }

    public function testFormBuilder(): void
    {
        $form = $this->factory->createBuilder(IdentityType::class)->getForm();

        self::assertEquals(2, $form->count());
        self::assertInstanceOf(FormInterface::class, $form->get('type'));
        self::assertInstanceOf(FormInterface::class, $form->get('endpoint'));
    }

    public function testGetDefaultOptions(): void
    {
        $type = new IdentityType();

        $optionResolver = new OptionsResolver();

        $type->configureOptions($optionResolver);

        $options = $optionResolver->resolve();

        self::assertFalse($options['csrf_protection']);
        self::assertEquals(Identity::class, $options['data_class']);
    }

    public function testSubmitValidData(): void
    {
        $data = [
            'type' => 'number',
            'endpoint' => '+46700000000',
        ];

        $form = $this->factory->create(IdentityType::class);

        $identity = (new Identity())
            ->setType('number')
            ->setEndpoint('+46700000000')
        ;

        // Submit the data to the form directly
        $form->submit($data);

        self::assertTrue($form->isSynchronized());

        /** @var Identity $formData */
        $formData = $form->getData();
        self::assertEquals($identity, $formData);
        self::assertIsString($formData->getType());
        self::assertIsString($formData->getEndpoint());

        $view = $form->createView();
        $children = $view->children;

        foreach (\array_keys($data) as $key) {
            self::assertArrayHasKey($key, $children);
        }
    }
}
