<?php

namespace App\DataFixtures;

use App\Entity\Facture;
use App\Entity\ProduitFacture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Produit;

class ProduitFactureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $products = $manager->getRepository(Produit::class)->findAll();
        $facture = $manager->getRepository(Facture::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $object = (new ProduitFacture())
                ->setQuantity($faker->randomDigit())
                ->setPrice($faker->randomFloat(2, 0, 100))
                ->setTva($faker->randomFloat(2, 0, 100));
            for ($j = 0; $j < $faker->numberBetween(1, 8); $j++) {
                $object->setProduit($faker->randomElement($products));
                $object->setFacture($faker->randomElement($facture));
            }
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
            FactureFixtures::class,
        ];
    }
}
