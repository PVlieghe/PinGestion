<?php

namespace App\Controller;

use App\Entity\Gamme;
use App\Form\GammeType;
use App\Repository\GammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gamme')]
class GammeController extends AbstractController
{
    #[Route('/', name: 'app_gamme_index', methods: ['GET'])]
    public function index(Request $request, GammeRepository $gammeRepository): Response
    {
        $forms = [];
        $gammes = $gammeRepository -> findAll();

        foreach($gammes as $gamme) {
            $formIndex = $this->createForm(GammeType::class, $gamme);
            $formIndex->handleRequest($request);
            $forms[$gamme->getId()] = $formIndex->createView();
        }
        $newGamme = new Gamme();
        $form = $this->createForm(GammeType::class, $newGamme);
        $form->handleRequest($request);
        return $this->render('gamme/index.html.twig', [
            'gammes' => $gammes,
            'form' => $form,
            'forms' => $forms,
            'success' => $request->query->get('success')

        ]);
    }

    #[Route('/new', name: 'app_gamme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $gamme = new Gamme();
        $form = $this->createForm(GammeType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compoData = $form ->getData()->getCompoGammes();
            foreach($compoData as $compo) {
                $compo->setGamme($gamme);
            }
            $entityManager->persist($gamme);
            $entityManager->flush();

            $success =  "La gamme \"" .$gamme -> getName()."\" a bien été ajoutée! ";
        }

        return $this->redirectToRoute('app_gamme_index', [
            'success' => $success
        ], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/edit', name: 'app_gamme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gamme $gamme, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $form = $this->createForm(GammeType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compoData = $form ->getData()->getCompoGammes();
            foreach($compoData as $compo) {
                $compo->setGamme($gamme);
            }
            $entityManager->persist($gamme);
            $entityManager->flush();

            $success =  "La gamme \"" .$gamme -> getName()."\" a bien été modifiée! ";

        }

        return $this->redirectToRoute('app_gamme_index', [
            'success' => $success
        ], Response::HTTP_SEE_OTHER);
    
    }

    #[Route('/{id}', name: 'app_gamme_delete', methods: ['POST'])]
    public function delete(Request $request, Gamme $gamme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamme->getId(), $request->getPayload()->getString('_token'))) {
            $success =  "La gamme \"" .$gamme->getName(). "\" a bien été supprimée!";
            $entityManager->remove($gamme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gamme_index', [
            'success' => $success
        ], Response::HTTP_SEE_OTHER);
    }
}
