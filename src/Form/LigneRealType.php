<?php

namespace App\Form;

use App\Entity\Poste;
use App\Entity\Machine;
use App\Entity\LigneReal;
use Doctrine\ORM\EntityRepository;
use App\Repository\PosteRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class LigneRealType extends AbstractType
{

    private $security;
    private $posteRepository;

    public function __construct(Security $security, PosteRepository $posteRepository)
    {
        $this->security = $security;
        $this->posteRepository = $posteRepository;
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
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
            'attr' => ['class' => 'form-control custom-form-control'],
            'class' => Poste::class,
            'choice_label' => 'name',
            'query_builder' => function (PosteRepository $pr) use ($user) {
                return $pr->createQueryBuilder('p')
                    ->innerJoin('p.qualifPostes', 'qp')
                    ->andWhere('qp.usr = :user')
                    ->setParameter('user', $user);
            },
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
