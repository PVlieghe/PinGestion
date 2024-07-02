<?php

namespace App\Form;

use App\Entity\Piece;
use App\Entity\Composition;
use App\Repository\PieceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pieceFabrique', EntityType::class, [
            'class' => Piece::class,
            'attr' => ['class' => 'form-control custom-form-control m-2'],
            'choice_label' => 'name',
            'label' => false,
            'query_builder' => function (PieceRepository $er) {
                return $er->createQueryBuilder('p')
                    ->where('p.intermediaire = :intermediaire')
                    ->setParameter('intermediaire', true);
            },
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Composition::class,
        ]);
    }
}