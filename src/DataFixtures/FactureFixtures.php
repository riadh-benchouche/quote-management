<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Devis;
use App\Entity\Facture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FactureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $clients = $manager->getRepository(Client::class)->findAll();
        $devis = $manager->getRepository(Devis::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $object = (new Facture())
                ->setDate($faker->dateTimeBetween('-6 months'))
                ->setClient($faker->randomElement($clients))
                ->setMontant($faker->randomFloat(2, 100, 1000))
                ->setMessage($faker->text(200))
                ->setDevis($faker->randomElement($devis))
                ->setEcheance($faker->dateTimeBetween('-6 months'))
                ->setStatus($faker->randomElement(['draft', 'paid', 'unpaid']));
            for ($j = 0; $j < $faker->numberBetween(1, 10); $j++) {
                $object->setDevis($faker->randomElement($devis))
                        ->setClient($faker->randomElement($clients));
            }
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixtures::class,
            DevisFixtures::class,
        ];
    }
}
