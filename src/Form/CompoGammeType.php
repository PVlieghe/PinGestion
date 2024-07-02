<?php

namespace App\Form;

use App\Entity\CompoGamme;
use App\Entity\Operation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompoGammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('operation', EntityType::class, [
                'class' => Operation::class,
                'attr' => ['class' => 'form-control custom-form-control m-2'],
                'choice_label' => 'content',
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompoGamme::class,
        ]);
    }
}
