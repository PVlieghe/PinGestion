<?php

use App\Entity\Realisation;
use App\Form\LigneRealType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('ligneReals', CollectionType::class, [
                'entry_type' => LigneRealType::class,
                'entry_options' =>  [],
                'by_reference' => false,
            ])
            // ... autres champs de Realisation
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisation::class,
        ]);
    }
}
