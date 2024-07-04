<?php

namespace App\Form;

use App\Entity\Poste;
use App\Entity\Machine;
use App\Entity\LigneReal;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class LigneRealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $operation = $options['operation'];

        $builder
        ->add('duree_hours', IntegerType::class, [
            'label' => 'Heures',
            'attr' => ['class' => 'form-control custom-form-control col-2'],
            'mapped' => false,
        ])
        ->add('duree_minutes', IntegerType::class, [
            'label' => 'Minutes',
            'attr' => ['class' => 'form-control custom-form-control col-2'],
            'mapped' => false,
        ])
        ->add('poste', EntityType::class, [
            'attr' => ['class' => 'form-control custom-form-control col-3'],
            'class' => Poste::class,
            'choice_label' => 'name',
        ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data && $data->getOperation()) {
                $operation = $data->getOperation();
                
                $form->add('machine', EntityType::class, [
                    'class' => Machine::class,
                    'attr' => ['class' => 'form-control custom-form-control col-3'],
                    'choice_label' => 'Name',
                    'query_builder' => function (EntityRepository $er) use ($operation) {
                        return $er->createQueryBuilder('m')
                            ->where('m IN (:machines)')
                            ->setParameter('machines', $operation->getQualifOperations()->map(function($qualif) {
                                return $qualif->getMachine();
                            })->toArray());
                    },
                ]);
            }});
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneReal::class,
            'operation' => null,
        ]);
    }
}
