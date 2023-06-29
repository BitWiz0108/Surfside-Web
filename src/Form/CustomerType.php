<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', TextType::class, [
                'label' => 'Company',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('first_name', TextType::class, [
                'label' => 'First Name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('state', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('postalcode', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('calendarUrl', UrlType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
