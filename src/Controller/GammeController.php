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
        $newGamme = new Gamme();
        $form = $this->createForm(GammeType::class, $newGamme);
        $form->handleRequest($request);
        return $this->render('gamme/index.html.twig', [
            'gammes' => $gammeRepository->findAll(),
            'form' => $form,
            'success' => null,

        ]);
    }

    #[Route('/new', name: 'app_gamme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
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

            return $this->redirectToRoute('app_gamme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gamme/new.html.twig', [
            'gamme' => $gamme,
            'form' => $form,
            'success' =>'La gamme '. $gamme->getName() .' a bien été ajoutée!'
        ]);
    }

    #[Route('/{id}', name: 'app_gamme_show', methods: ['GET'])]
    public function show(Gamme $gamme): Response
    {
        return $this->render('gamme/show.html.twig', [
            'gamme' => $gamme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gamme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gamme $gamme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GammeType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gamme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gamme/edit.html.twig', [
            'gamme' => $gamme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gamme_delete', methods: ['POST'])]
    public function delete(Request $request, Gamme $gamme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamme->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($gamme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gamme_index', [], Response::HTTP_SEE_OTHER);
    }
}
