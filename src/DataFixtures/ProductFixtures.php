<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Produit;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $categories = $manager->getRepository(Categorie::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $object = (new Produit())
                ->setName($faker->sentence)
                ->setPrixHt($faker->randomFloat(2, 0, 100))
                ->setTva($faker->randomFloat(2, 0, 100))
                ->setMontant($faker->randomFloat(2, 0, 100))
            ;
            for ($j = 0; $j < $faker->numberBetween(1, 10); $j++) {
                $object->setCategorie($faker->randomElement($categories));
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
