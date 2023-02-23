<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Quizz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du quizz',
                'attr' => [
                    'placeholder' => 'Nom du quizz'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'choice_label' => 'name'
            ])
            ->add('questions', EntityType::class, [
                'class' => Question::class,
                'multiple' => true,
                'choice_label' => 'question'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quizz::class,
        ]);
    }
}
