<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\QualifMachine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosteToMachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('machine', EntityType::class, [
                'class' => Machine::class,
                'attr' => ['class' => 'form-control custom-form-control m-2'],
                'choice_label' => 'Name',
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QualifMachine::class,
        ]);
    }
}
