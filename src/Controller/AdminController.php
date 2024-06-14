<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private $userRepository;
    private $entityManager;


    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard', name: 'app_admin_dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = [];
            if ($form->get('role_admin')->getData()) {
                $roles[] = 'ROLE_ADMIN';
            }
            if ($form->get('role_compta')->getData()) {
                $roles[] = 'ROLE_COMPTA';
            }
            if ($form->get('role_atelier')->getData()) {
                $roles[] = 'ROLE_ATELIER';
            }
            $newUser->setRoles($roles);

            // Hachage du mot de passe
            $newUser->setPassword($passwordHasher->hashPassword(
                $newUser,
                'User1'
            ));

            $this->entityManager->persist($newUser);
            $this->entityManager->flush();
            return $this->render('admin/dashboard.html.twig', [
                'users' => $this->userRepository->findAll(),
                'success' => 'L\'utilisateur ' . $newUser->getUsername() . ' a bien été créé!',
                'form' => $form
            ]);
        }
        return $this->render('admin/dashboard.html.twig', [
            'users' => $this->userRepository->findAll(),
            'success' => null,
            'form' => $form
        ]);
    }


    #[Route('/adminup/{id}', name: 'app_admin_adminup', methods: ['POST'])]
    public function adminup(User $user): Response
    {
        // Ajouter le rôle ROLE_ADMIN à l'utilisateur
        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/atelierup/{id}', name: 'app_admin_atelierup', methods: ['POST'])]
    public function atelierup(User $user): Response
    {
        // Ajouter le rôle ROLE_ADMIN à l'utilisateur
        $roles = $user->getRoles();
        if (!in_array('ROLE_ATELIER', $roles)) {
            $roles[] = 'ROLE_ATELIER';
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_admin_dashboard');
    }

    
    #[Route('/comptaup/{id}', name: 'app_admin_comptaup', methods: ['POST'])]
    public function conptaup(User $user): Response
    {
        // Ajouter le rôle ROLE_ADMIN à l'utilisateur
        $roles = $user->getRoles();
        if (!in_array('ROLE_COMPTA', $roles)) {
            $roles[] = 'ROLE_COMPTA';
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/admindown/{id}', name: 'app_admin_admindown', methods: ['POST'])]
    public function admindown(User $user): Response
    {
        // Retirer le rôle ROLE_ADMIN à l'utilisateur
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $roles = array_diff($roles, ['ROLE_ADMIN']);
            $user->setRoles($roles);

            // Sauvegarder les modifications dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        // Rediriger vers le tableau de bord admin
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/atelierdown/{id}', name: 'app_admin_atelierdown', methods: ['POST'])]
    public function atelierdown(User $user): Response
    {
        // Retirer le rôle ROLE_ADMIN à l'utilisateur
        $roles = $user->getRoles();
        if (in_array('ROLE_ATELIER', $roles)) {
            $roles = array_diff($roles, ['ROLE_ATELIER']);
            $user->setRoles($roles);

            // Sauvegarder les modifications dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        // Rediriger vers le tableau de bord admin
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/comptadown/{id}', name: 'app_admin_comptadown', methods: ['POST'])]
    public function down(User $user): Response
    {
        // Retirer le rôle ROLE_ADMIN à l'utilisateur
        $roles = $user->getRoles();
        if (in_array('ROLE_COMPTA', $roles)) {
            $roles = array_diff($roles, ['ROLE_COMPTA']);
            $user->setRoles($roles);

            // Sauvegarder les modifications dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        // Rediriger vers le tableau de bord admin
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/add', name: 'app_admin_add', methods: ['GET','POST'])]
    public function add(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = [];
            if ($form->get('admin')->getData()) {
                $roles[] = 'ROLE_ADMIN';
            }
            if ($form->get('compta')->getData()) {
                $roles[] = 'ROLE_COMPTA';
            }
            if ($form->get('atelier')->getData()) {
                $roles[] = 'ROLE_ATELIER';
            }
            $user->setRoles($roles);

            // Hachage du mot de passe
            $user->setPassword($passwordHasher->hashPassword(
                $user,
                'User1'
            ));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('user_list');
        }
        
        return $this->render('admin/dashboard.html.twig', [
            'success' =>'l\'utilisateur a bien été ajouté!'
        ]);
    }


}
