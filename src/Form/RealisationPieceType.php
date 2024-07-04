<?php

use App\Entity\Piece;
use App\Repository\PieceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RealisationPieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('piece', EntityType::class, [
                'class' => Piece::class,
                'attr' => ['class' => 'form-control custom-form-control m-2'],
                'query_builder' => function (PieceRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.fabrique = :fabrique')
                        ->setParameter('fabrique', true);
                },
                'choice_label' => 'name',
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
