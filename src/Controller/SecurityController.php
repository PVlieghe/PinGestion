<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $translatedError = null;

        if ($error) {
            $translatedError = $this->translateError($error->getMessageKey());
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $translatedError,
        ]);
    }

    private function translateError(string $messageKey): string
    {
        $translations = [
            'Invalid credentials.' => 'Les informations d\'identification ne sont pas valides.',
            'Account is locked.' => 'Votre compte est verrouillé. Veuillez contacter l\'administrateur.',
            'Account is disabled.' => 'Votre compte est désactivé. Veuillez contacter l\'administrateur.',
            // Ajoutez d'autres traductions personnalisées ici
        ];

        return $translations[$messageKey] ?? 'Une erreur d\'authentification s\'est produite.';
    }
}
