<?php

namespace App\Form;

use App\Entity\Poste;
use App\Entity\QualifPoste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserToPosteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste', EntityType::class, [
                'class' => Poste::class,
                'attr' => ['class' => 'form-control custom-form-control m-2'],
                'choice_label' => 'name',
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QualifPoste::class,
        ]);
    }
}
