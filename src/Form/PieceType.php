<?php

namespace App\Form;

use App\Entity\Gamme;
use App\Entity\Piece;
use App\Form\CompositionType;
use App\Repository\GammeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => ['class' => 'form-control custom-form-control'],
            'label' => '<i class="bi bi-card-heading"></i> Nom de la pièce :', // Ajoutez l'icône au label
            'label_html' => true, // Indique que le label contient du HTML
        ])
        ->add('libelle', TextType::class, [
            'attr' => ['class' => 'form-control custom-form-control'],
            'label' => '<i class="bi bi-card-heading"></i> Libellé de la pièce :', // Ajoutez l'icône au label
            'label_html' => true, // Indique que le label contient du HTML
        ])
            ->add('prix_vente', NumberType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control custom-form-control'],
                'label' => '<i class="bi bi-currency-euro"></i> Prix de vente (ne rien mettre si la pièce n\'est pas en vente)', // Ajoutez l'icône au label
                'label_html' => true, // Indique que le label contient du HTML
                ])
            ->add('prix_achat', NumberType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control custom-form-control'],
                'label' => '<i class="bi bi-currency-euro"></i> Prix d\'achat (ne rien mettre si la pièce n\'a pas été achetée)', // Ajoutez l'icône au label
                'label_html' => true, // Indique que le label contient du HTML
                ])
            ->add('intermediaire', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'label'    => 'Cette pièce peut-elle être utilisée pour fabriquer d\'autres pièces?',
                'required' => false,
                
            ])
            ->add('fabrique', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'label'    => 'Cette pièce est-elle fabriquable depuis l\'atelier?',
                'required' => false,
               
            ])
            ->add('stock', NumberType::class, [
                'attr' => ['class' => 'form-control custom-form-control'],
                'label' => 'Stock de cette pièce disponible:', // Ajoutez l'icône au label
                ])
            ->add('gamme', EntityType::class, [
                'class' => Gamme::class,
                'attr' => ['class' => 'form-control custom-form-control'],
                'choice_label' => 'name',
                'placeholder' => 'Aucune pièce sélectionnée',
                'required' => false,
                'label' => 'Gammes disponibles à associer à la pièce :',
                'query_builder' => function (GammeRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->where('g.piece IS NULL');
                }
            ])
            ->add('compositions', CollectionType::class, [
                'entry_type' => CompositionType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => 'Intermédiaires composant cette pièce:',
                'allow_add' => true,
                'allow_delete' => true

                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
        ]);
    }
}
