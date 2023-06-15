<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $object = (new Client())
                ->setEmail($faker->email)
                ->setCompanyName($faker->company)
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                ->setAddress($faker->address)
                ->setCity($faker->city)
                ->setPhone($faker->phoneNumber);
            $manager->persist($object);
        }

        $manager->flush();
    }
}
