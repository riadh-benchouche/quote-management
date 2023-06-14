<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', null, [
                'attr' => ['class' => 'bg-red-600'],
            ])
            ->add('date')
            ->add('quantity')
            ->add('prixHt')
            ->add('tva')
            ->add('montant')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Brouillon' => 'brouillon',
                    'Facturé' => 'facture',
                    'Non accepté' => 'nonAccepte',
                    'Refusé' => 'refuse',
                ],
                'data' => 'brouillon', // Valeur initiale du champ
            ])
            ->add('client')
            ->add('produits');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
