<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add( 'firstName', null, [
                'attr' => [
                    'class'     => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50'
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label mb-2'
                ]
            ])
            ->add('lastName', null, [
                'attr' => [
                    'class'     => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mb-2'
                ]
            ])
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
            ->add( 'address', null, [
                'attr' => [
                    'class'  => 'form-control',
                ],
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form-label mb-2'
                ]
            ])
            ->add( 'city', null, [
                'attr' => [
                    'class'  => 'form-control',
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form-label mb-2'
                ]
            ])
            ->add( 'cp', null, [
                'attr' => [
                    'class'  => 'form-control',
                ],
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'form-label mb-2'
                ]
            ])
            // ->add('roles')
            // ->add('password', PasswordType::class, [
            //     "attr" => [
            //         "class" => "form-control"
            //     ],
            //     "label" => "Mot de passe",
            //     "label_attr" => ["class" => "form-label"],
            // ])
            // ->add('password', CheckboxType::class, [
            //     'label' => 'Show password',
                
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
