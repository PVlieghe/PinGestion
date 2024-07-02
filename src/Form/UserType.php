<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UserToGammeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control custom-form-control']
            ])
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control custom-form-control']
            ])
            ->add('role_admin', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'label'    => 'Administrateur',
                'required' => false,
                'mapped' => false,
            ])
            ->add('role_atelier', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'label'    => 'Atelier',
                'required' => false,
                'mapped' => false,
            ])
            ->add('role_compta', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'label'    => 'Compta',
                'required' => false,
                'mapped' => false,
            ])
            ->add('qualifPostes', CollectionType::class, [
                'entry_type' => UserToPosteType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => ' (Facultatif) Postes pour lesquels l\'utilisateur sera qualifié :',
                'allow_add' => true,
                'allow_delete' => true,

            ])
            ->add('gammes', CollectionType::class, [
                'entry_type' => UserToGammeType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => 'Ajouter l\'utilisateur comme référent de gammes:',
                'allow_add' => true,
                'allow_delete' => true,

            ])
        ;
        if (!$options['is_edit']) {
            $builder->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control custom-form-control']
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false, // default value is false
        ]);
    }
}
