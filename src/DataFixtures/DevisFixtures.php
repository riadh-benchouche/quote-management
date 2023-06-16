<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Devis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DevisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $clients = $manager->getRepository(Client::class)->findAll();

        for ($i = 0; $i < 15; $i++) {
            $object = (new Devis())
                ->setDate($faker->dateTimeBetween('-6 months'))
                ->setClient($faker->randomElement($clients))
                ->setMontant($faker->randomFloat(2, 100, 1000))
                ->setMessage($faker->text(200))
                ->setStatus($faker->randomElement(['draft', 'accepted', 'rejected', 'invoiced']));
            for ($j = 0; $j < $faker->numberBetween(1, 10); $j++) {
                $object->setClient($faker->randomElement($clients));
            }
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixtures::class,
        ];
    }
}
