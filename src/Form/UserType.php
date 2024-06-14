<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
            ->add('password', PasswordType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
