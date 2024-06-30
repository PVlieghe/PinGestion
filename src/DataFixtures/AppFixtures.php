<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Gamme;
use App\Entity\Machine;
use App\Entity\Operation;
use App\Entity\Poste;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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

        $secondUser = new User();
        $secondUser->setEmail('user1@user.fr');
        $secondUser->setUsername('Mr Utilisateur 1');
        
        // encode the plain password
        $secondUser->setPassword(
            $this->passwordHasher->hashPassword(
                $secondUser,
                'User1'
            )
        );

        $manager->persist($firstUser);
        $manager->persist($secondUser);

        for($i=0; $i<20; $i++){
            $ope = new Operation();
            $ope->setContent("opération".$i);
            $manager->persist($ope);
        }

        for($i=0; $i<20; $i++){
            $poste = new Poste();
            $poste->setName("Poste ".$i);
            $manager->persist($poste);
        }
        
        
        for($i=0; $i<10; $i++){
            $machine = new Machine();
            $machine->setName("Machine ".$i);
            $manager->persist($machine);
        }     

        $manager->flush();
    }
}
