<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fresh\SinchBundle\Form\Type;

use Fresh\SinchBundle\Model\CallbackRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as CoreType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * CallbackRequestType.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class CallbackRequestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('event', CoreType\ChoiceType::class, [
                'choices' => [
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
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('version', CoreType\IntegerType::class, [
                'required' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CallbackRequest::class,
            'csrf_protection' => false,
            'method' => Request::METHOD_POST,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
