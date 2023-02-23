<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnswerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $questions = $manager->getRepository(Question::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $object = (new Answer())
                ->setAnswer($faker->sentence)
                ->setQuestion($faker->randomElement($questions))
                ->setIsValid($faker->boolean)
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            QuestionFixtures::class,
        ];
    }
}
