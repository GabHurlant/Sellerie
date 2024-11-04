<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager) : void
    {
        $faker = Factory::create('fr_FR');
        // creation of users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            //hachage du mot de passe avec param de sécurité de $user
            //dans config/packages/security.yaml
            $hash = $this->hasher->hashPassword($user, 'password');
            if ($i === 7) {
                $user->setRoles(['ROLE_ADMIN'])->setMail($faker->email());
            } else {
                $user->setMail($faker->email());
            }
            $user->setPrenom($faker->firstName());
            $user->setNom($faker->lastName());
            $user->setPassword($hash);
            $manager->persist($user);
        }

        $manager->flush();
    }
}