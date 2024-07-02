<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Gamme;
use App\Entity\Piece;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'];

        $builder
            ->add('Name', TextType::class, [
                'attr' => ['class' => 'form-control custom-form-control'],
                'label' => '<i class="bi bi-card-heading"></i> Nom de la machine :', // Ajoutez l'icône au label
                'label_html' => true, // Indique que le label contient du HTML
            ])
            ->add('compoGammes', CollectionType::class, [
                'entry_type' => CompoGammeType::class, // Type de champ pour chaque élément de la collection
                'entry_options' => [
                    'label' => false, 
                ],
                'label' => 'Opérations composant la gamme :', // Ajoutez l'icône au label
                'allow_add' => true, // Permettre à l'utilisateur d'ajouter de nouveaux éléments
                'allow_delete' => true, // Permettre à l'utilisateur de supprimer des éléments existants
            ])
            ->add('piece', EntityType::class, [
                'label_html' => true,
                'label' => '<i class="bi bi-box"></i> (facultatif) Pièce associée :',
                'attr' => ['class' => 'form-control custom-form-control'],
                'class' => Piece::class,
                'choice_label' => 'name',
            ])
            ->add('referent', EntityType::class, [
                'attr' => ['class' => 'form-control custom-form-control'],
                'label_html' => true,
                'label' => '<i class="bi bi-person"></i> Référent de la gamme :', // Ajoutez l'icône au label
                'class' => User::class,
                'choice_label' => 'username',
                'data' => $user,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gamme::class,
            'user' => null,
        ]);
    }
}
