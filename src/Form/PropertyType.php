<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('state', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('postalcode', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('latitude', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('longitude', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('bedrooms', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('bathrooms', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('square_feet', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('kings', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('queens', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('twins', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('towels', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('hand_towels', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('wash_cloths', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('instructions', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('door_code', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
