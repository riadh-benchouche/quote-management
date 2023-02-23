<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Quizz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $quizz = $manager->getRepository(Quizz::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $object = (new Question())
                ->setQuizz($faker->randomElement($quizz))
                ->setQuestion($faker->sentence)
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            QuizzFixtures::class,
        ];
    }
}
