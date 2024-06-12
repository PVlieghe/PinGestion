<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $firstUser = new User();
        $firstUser->setEmail('admin1@admin.fr');
        $firstUser->setUsername('Mr Administrateur 1');
        
        // encode the plain password
        $firstUser->setPassword(
            $this->passwordHasher->hashPassword(
                $firstUser,
                'Admin1'
            )
        );
        $firstUser->setRoles(['ROLE_ADMIN']);
        $manager->persist($firstUser);

        $manager->flush();
    }
}
