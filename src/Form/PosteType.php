<?php

namespace App\Form;

use App\Entity\Poste;
use App\Form\PosteToMachineType;
use App\Form\PosteToUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PosteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control custom-form-control'],
                'label' => '<i class="bi bi-card-heading"></i> Nom du poste :', // Ajoutez l'icône au label
                'label_html' => true, // Indique que le label contient du HTML
            ])
            ->add('qualifMachines', CollectionType::class, [                
                'entry_type' => PosteToMachineType::class, 
                'entry_options' => [
                    'label' => false,
                ],
                'label' => " (Facultatif) Machines utiisables sur ce poste : ",
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poste::class,
        ]);
    }
}
