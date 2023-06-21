<?php

namespace App\Form;

use App\Entity\Clean;
use App\Entity\Housekeeper;
use App\Entity\Property;
use App\Repository\HousekeeperRepository;
use App\Repository\PropertyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CleanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('property', EntityType::class, [
                'class' => Property::class,
                'query_builder' => function (PropertyRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                },
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('housekeepers', EntityType::class, [
                'required' => false,
                'mapped' => false,
                'class' => Housekeeper::class,
                'query_builder' => function (HousekeeperRepository $hr) {
                    return $hr->createQueryBuilder('h')
                        ->orderBy('h.last_name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control',
                ],
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('scheduled', DateTimeType::class, [
                'label' => 'Scheduled Date/Time',
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('start', DateTimeType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('end', DateTimeType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('notes', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clean::class,
        ]);
    }
}
