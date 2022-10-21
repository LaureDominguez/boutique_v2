<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//added "PasswordType" path
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class'     => 'form-control',
                    'minlenght' => '5',
                    'maxlenght' => '100'
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label mb-2'
                ]
            ])
            //->add('roles')
            //added "PasswordType" class
            ->add('password', PasswordType::class, [
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Mot de passe",
                "label_attr" => ["class" => "form-label"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
