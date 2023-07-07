<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message'
                ,null,[
                    'required' => true,
                ])
            ->add('date',null,[
                'required' => true,
            ])
            ->add('quantity',null,[
                'required' => true,
            ])
            ->add('echeance',null,[
                'required' => true,
            ])
            ->add('status',null,[
                'required' => true,
            ])
            ->add('prixHt',null,[
                'required' => true,
            ])
            ->add('tva',null,[
                'required' => true,
            ])
            ->add('montant',null,[
                'required' => true,
            ])
            ->add('client',null,[
                'required' => true,
            ])
            ->add('produits',null,[
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
