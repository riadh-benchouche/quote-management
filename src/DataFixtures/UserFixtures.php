<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $pwd = '123456';

        $object = (new User())
            ->setEmail('user@user.fr')
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setRoles([])
            ->setPassword($pwd)
            ->setIsVerified(true);
        $manager->persist($object);

        $object = (new User())
            ->setEmail('admin@user.fr')
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd)
            ->setIsVerified(true);
        $manager->persist($object);

        $object = (new User())
            ->setEmail('comptable@user.fr')
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setRoles(['ROLE_ACCOUNTANT'])
            ->setPassword($pwd)
            ->setIsVerified(true);
        $manager->persist($object);

        $object = (new User())
            ->setEmail('salarie@user.fr')
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setRoles(['ROLE_EMPLOYEE'])
            ->setPassword($pwd)
            ->setIsVerified(true);
        $manager->persist($object);

        $manager->flush();
    }
}
