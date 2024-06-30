<?php

namespace App\Form;

use App\Entity\Gamme;
use App\Entity\CompoGamme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeToGammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gamme', EntityType::class, [
                'class' => Gamme::class,
                'attr' => ['class' => 'form-control custom-form-control m-2'],
                'choice_label' => 'name',
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
