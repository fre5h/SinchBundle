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
class IdentityTypeTest extends TypeTestCase
{
    public function testGetBlockPrefix()
    {
        $this->assertEmpty((new IdentityType)->getBlockPrefix());
    }

    public function testFormBuilder()
    {
        $form = $this->factory->createBuilder(IdentityType::class)->getForm();

        $this->assertEquals(2, $form->count());
        $this->assertInstanceOf(FormInterface::class, $form->get('type'));
        $this->assertInstanceOf(FormInterface::class, $form->get('endpoint'));
    }

    public function testGetDefaultOptions()
    {
        $type = new IdentityType();

        $optionResolver = new OptionsResolver();

        $type->configureOptions($optionResolver);

        $options = $optionResolver->resolve();

        $this->assertFalse($options['csrf_protection']);
        $this->assertEquals(Identity::class, $options['data_class']);
    }

    public function testSubmitValidData()
    {
        $data = [
            'type' => 'number',
            'endpoint' => '+46700000000',
        ];

        $form = $this->factory->create(IdentityType::class);

        $identity = (new Identity())->setType('number')
                                    ->setEndpoint('+46700000000');

        // Submit the data to the form directly
        $form->submit($data);

        $this->assertTrue($form->isSynchronized());

        /** @var \Fresh\SinchBundle\Model\Identity $formData */
        $formData = $form->getData();
        $this->assertEquals($identity, $formData);
        $this->assertInternalType('string', $formData->getType());
        $this->assertInternalType('string', $formData->getEndpoint());

        $view = $form->createView();
        $children = $view->children;

        foreach (\array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
