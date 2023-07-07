<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company_name',null,[
            'required' => true,
        ])
            ->add('email',null,[
                'required' => true,
            ])
            ->add('lastname',null,[
                'required' => true,
            ])
            ->add('firstname',null,[
                'required' => true,
            ])
            ->add('address',null,[
                'required' => true,
            ])
            ->add('zipcode',null,[
                'required' => true,
            ])
            ->add('city',null,[
                'required' => true,
            ])
            ->add('phone',null,[
                'required' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
