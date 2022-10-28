<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                // 'type' => ShowHidePasswordType::class,
                "attr" => ["class" => "form-control"],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => [
                    'class' => 'password-field form-control',
                    'value'  => "",
                'input' => 'required'
                    ]],
                'required' => true,
                'first_options'  => [
                    'label' => 'Nouveau mot de passe',
                    "label_attr" => [
                        'class' => 'password-field form-label',
                        'value'  => "",
                        'input' => 'required'
                    ]
                ],
                'second_options' => [
                    'label' => 'Retapez le nouveau mot de passe',
                    "label_attr" => [
                        'class' => 'password-field form-label',
                        'value'  => "",
                    'input' => 'required'
                    ]
                ],
                "label_attr" => ["class" => "password-field form-control"]
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
