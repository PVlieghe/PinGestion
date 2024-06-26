<?php

namespace App\Form;

use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'attr' => ['class' => 'form-control custom-form-control'],
                'label' => '<i class="bi bi-card-heading"></i> Nom/Contenu', // Ajoutez l'icône au label
                'label_html' => true, // Indique que le label contient du HTML
            ])
            ->add('compoGamme', CollectionType::class, [                
                'entry_type' => OpeToGammeType::class, 
                'entry_options' => [
                    'label' => false,
                ],
                'label' => " (Facultatif) Gammes composées de cette opération :",
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('qualifOperations', CollectionType::class, [
                'entry_type' => OpeToMachineType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => ' (Facultatif) Machines adaptées pour cette opération :',
                'allow_add' => true,
                'allow_delete' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
