<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteType;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/poste')]
class PosteController extends AbstractController
{
    #[Route('/', name: 'app_poste_index', methods: ['GET'])]
    public function index(Request $request,  PosteRepository $posteRepository ): Response
    {
        
        $postes = $posteRepository -> findAll();

        $forms = [];

        foreach ($postes as $poste) {
            $formIndex = $this->createForm(PosteType::class, $poste);
            $formIndex->handleRequest($request);
            $forms[$poste->getId() ] = $formIndex->createView();
        }

        $newPoste = new Poste();
        $form = $this->createForm(PosteType::class, $newPoste);
        $form->handleRequest($request);

        return $this->render('poste/index.html.twig', [
            'postes' => $postes,
            'form' => $form,
            'forms' => $forms,
            'success' => $request->query->get('success')
        ]);
    }

    #[Route('/new', name: 'app_poste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualifMachine = $form ->getData()->getQualifMachines();
            $qualifPoste = $form ->getData()->getQualifPostes();
            foreach($qualifMachine as $mac) {
                $mac->setPoste($poste);
            }
            foreach($qualifPoste as $pos) {
                $pos->setPoste($poste);
            }

            $entityManager->persist($poste);
            $entityManager->flush();
            $success =  "Le poste \"" .$poste -> getName()."\" a bien été ajouté! ";
        }

        return $this->redirectToRoute('app_poste_index', [
            'success' => $success
        ]);
    }

    #[Route('/{id}/edit', name: 'app_poste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualifMachine = $form ->getData()->getQualifMachines();
            $qualifPoste = $form ->getData()->getQualifPostes();
            foreach($qualifMachine as $mac) {
                $mac->setPoste($poste);
            }
            foreach($qualifPoste as $poste) {
                $poste->setPoste($poste);
            }

            $entityManager->persist($poste);
            $entityManager->flush();

            $success =  "Le poste \"" .$poste->getName(). "\" a bien été modifié!";
        }

        return $this->redirectToRoute('app_poste_index', [
            'success' => $success
        ]);
    }

    #[Route('/{id}', name: 'app_poste_delete', methods: ['POST'])]
    public function delete(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        $success = null;

        if ($this->isCsrfTokenValid('delete'.$poste->getId(), $request->getPayload()->getString('_token'))) {
            $success =  "Le poste \"" .$poste->getName(). "\" a bien été supprimé!";
            $entityManager->remove($poste);
            $entityManager->flush();

        }

        return $this->redirectToRoute('app_poste_index', [
            'success' => $success
        ], Response::HTTP_SEE_OTHER);
    }
}
