<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $defaultLastName = $options['data']['lastname'] ?? '';
        $defaultFirstName = $options['data']['firstname'] ?? '';
        $defaultEmail = $options['data']['email'] ?? '';

        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'data' => $defaultLastName,
                'empty_data' => $defaultLastName,
                'required' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'data' => $defaultFirstName,
                'empty_data' => $defaultFirstName,
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'data' => $defaultEmail,
                'empty_data' => $defaultEmail,
                'required' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('newPassword', PasswordType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Nouveau mot de passe',
            ]);

        // ->add('avatarFile', VichImageType::class, [
        //     'required' => false,
        //     'allow_delete' => true,
        //     'delete_label' => 'Supprimer l\'image actuelle',
        //     'download_label' => 'Télécharger l\'image actuelle',
        //     'download_uri' => true,
        // ]);
    }
}
