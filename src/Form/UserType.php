<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

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
                'label_attr' => ['class' => 'form-label']
            ])
            // ->add('password', ShowHidePasswordType::class, )
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                "attr" => [ "class" => "form-control" ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    "label_attr" => ["class" => "form-label"]
                ],
                'second_options' => [
                    'label' => 'Retapez le mot de passe',
                    "label_attr" => ["class" => "form-label"]
                ],
                "label_attr" => ["class" => "form-label"]
            ])
        ;
    }
    
    // public function getParent(){
    //     return PasswordType::class;
    // }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
