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
        $secondUser->setEmail('atelier1@atelier.fr');
        $secondUser->setUsername('Mr Atelier 1');
        
        // encode the plain password
        $secondUser->setPassword(
            $this->passwordHasher->hashPassword(
                $secondUser,
                'Atelier1'
            )
        );
        
        $secondUser->setRoles(['ROLE_ATELIER']);


        $thirdUser = new User();
        $thirdUser->setEmail('compta1@compta.fr');
        $thirdUser->setUsername('Mr Compta 1');
        
        // encode the plain password
        $thirdUser->setPassword(
            $this->passwordHasher->hashPassword(
                $thirdUser,
                'Compta1'
            )
        );
        
        $thirdUser->setRoles(['ROLE_COMPTA']);

        $manager->persist($firstUser);
        $manager->persist($secondUser);
        $manager->persist($thirdUser);

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
