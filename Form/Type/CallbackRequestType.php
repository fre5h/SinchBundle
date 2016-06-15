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
 * CallbackRequestType.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class CallbackRequestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event', CoreType\ChoiceType::class, [
                'choices'  => [
                    'incomingSms' => 'incomingSms',
                ],
                'required' => true,
            ])
            ->add('to', IdentityType::class, [
                'required' => true,
            ])
            ->add('from', IdentityType::class, [
                'required' => true,
            ])
            ->add('message', CoreType\TextType::class, [
                'required' => true,
            ])
            ->add('timestamp', CoreType\DateTimeType::class, [
                'widget'   => 'single_text',
                'required' => true,
            ])
            ->add('version', CoreType\IntegerType::class, [
                'required' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'Fresh\SinchBundle\Model\CallbackRequest',
            'csrf_protection' => false,
            'method'          => 'POST',
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