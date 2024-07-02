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

        $users = $this->userRepository->findAll();

        $forms = [];

        foreach ($users as $user) {
            $formIndex = $this->createForm(UserType::class, $user, ['is_edit' => true]);
            $formIndex->handleRequest($request);
            $forms[$user->getId() ] = $formIndex->createView();
        }

        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser);
        $form->handleRequest($request);

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'success' => $request->query->get('success'),
            'form' => $form,
            'forms' => $forms
        ]);
    }


    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $form = $this->createForm(UserType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gammes = $form ->getData()->getGammes();
            $qualifPoste = $form ->getData()->getQualifPostes();
            foreach($gammes as $gamme) {
                $gamme->setReferent($user);
            }
            foreach($qualifPoste as $poste) {
                $poste->setUsr($user);
            }

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
            $user->setRoles($roles);

            $entityManager->persist($user);
            $entityManager->flush();

            $success =  "L'utilisateur \"" .$user->getUsername(). "\" a bien été modifié!";
        }

        return $this->redirectToRoute('app_admin_dashboard', [
            'success' => $success
        ]);
    }

    #[Route('/add', name: 'app_admin_add', methods: ['GET','POST'])]
    public function add(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $success = null;
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gammes = $form ->getData()->getGammes();
            $qualifPoste = $form ->getData()->getQualifPostes();
            foreach($gammes as $gamme) {
                $gamme->setReferent($user);
            }
            foreach($qualifPoste as $poste) {
                $poste->setUsr($user);
            }

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
            $user->setRoles($roles);

            // Hachage du mot de passe
            $pw = $form->getData()->getPassword();
            $user->setPassword($passwordHasher->hashPassword(
                $user,
                $pw
            ));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $success = " L'utilisateur ". $user->getUsername(). " a bien été créé .";
            
        }
        
        return $this->redirectToRoute('app_admin_dashboard', [
            'success' =>$success
        ]);
    }


}
