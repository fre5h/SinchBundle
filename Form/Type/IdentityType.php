<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as CoreType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * IdentityType.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class IdentityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', CoreType\TextType::class, [
                'required' => true,
            ])
            ->add('endpoint', CoreType\TextType::class, [
                'required' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'Fresh\SinchBundle\Model\Identity',
            'csrf_protection' => false,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}