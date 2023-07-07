<?php

namespace App\Form;

use App\Entity\ProduitDevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Produit;

class ProduitDevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, [
                'required' => true,
            ])
            ->add('tva', NumberType::class, [
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'required' => true,
            ])
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'name',
                'required' => true,

            ])
            ->add('devis');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitDevis::class,
        ]);
    }
}
