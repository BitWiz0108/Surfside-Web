<?php

namespace App\Form;

use App\Entity\CleanLinen;
use App\Entity\Linen;
use App\Repository\LinenRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CleanLinenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('linen', EntityType::class, [
                'class' => Linen::class,
                'query_builder' => function (LinenRepository $lr) {
                    return $lr->createQueryBuilder('l')
                        ->andWhere('l.units > 0')
                        ->orderBy('l.name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('units', IntegerType::class, [
                'label' => 'Units Used',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CleanLinen::class,
        ]);
    }
}
