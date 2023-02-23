<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Quizz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuizzFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 0; $i < 10; $i++) {
            $object = (new Quizz())
                ->setName('Quizz ' . $faker->colorName)
            ;

            for ($j = 0; $j < $faker->numberBetween(1, 3); $j++) {
                $object->addCategory($faker->randomElement($categories));
            }

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
