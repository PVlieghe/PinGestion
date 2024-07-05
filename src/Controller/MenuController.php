<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route(path: '/menu', name: 'app_menu_main')]
    public function mainMenu(Security $security): Response
    {
               // Récupérer l'utilisateur actuellement connecté
               $user = $security->getUser();

               // Vérifier le rôle de l'utilisateur
               if ($this->isGranted('ROLE_ADMIN')) {
                   // Redirection pour l'administrateur
                   return $this->redirectToRoute('app_menu_admin');
               } elseif ($this->isGranted('ROLE_ATELIER')) {
                   // Redirection pour le rôle "role_atelier"
                   return $this->redirectToRoute('app_menu_atelier');
               } else {
                   // Redirection pour le rôle "role_compta"
                   return $this->redirectToRoute('app_menu_compta');
               } 
    }

    #[Route(path: '/atelier/menu', name: 'app_menu_atelier')]
    public function atelierMenu(): Response
    {
        return $this->render('menu/atelier.html.twig', []);
    }

    #[Route(path: '/compta/menu', name: 'app_menu_compta')]
    public function comptaMenu(): Response
    {
        return $this->render('menu/compta.html.twig', []);
    }
    #[Route(path: '/admin/menu', name: 'app_menu_admin')]
    public function adminMenu(): Response
    {
        return $this->render('menu/admin.html.twig', []);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
