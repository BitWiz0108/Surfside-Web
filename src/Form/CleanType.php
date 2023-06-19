<?php

namespace App\Form;

use App\Entity\Clean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CleanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('scheduled')
            ->add('start')
            ->add('end')
            ->add('notes')
            ->add('created')
            ->add('modified')
            ->add('property')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clean::class,
        ]);
    }
}
