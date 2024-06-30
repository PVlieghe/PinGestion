<?php

namespace App\Form;

use App\Entity\Machine;
use App\Form\MachineToOpeType;
use App\Form\MachineToPosteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'attr' => ['class' => 'form-control custom-form-control'],
                'label' => '<i class="bi bi-card-heading"></i> Nom de la machine :', // Ajoutez l'icône au label
                'label_html' => true, // Indique que le label contient du HTML
            ])
            ->add('qualifMachines', CollectionType::class, [                
                'entry_type' => MachineToPosteType::class, 
                'entry_options' => [
                    'label' => false,
                ],
                'label' => " (Facultatif) Postes qualifiés pour réaliser l'opération : ",
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('qualifOperations', CollectionType::class, [
                'entry_type' => MachineToOpeType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => ' (Facultatif) Opérations réalisables avec cette machine :',
                'allow_add' => true,
                'allow_delete' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}
