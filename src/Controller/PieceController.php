<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Form\PieceType;
use App\Repository\PieceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('atelier/piece')]
class PieceController extends AbstractController
{
    #[Route('/', name: 'app_piece_index', methods: ['GET'])]
    public function index(PieceRepository $pieceRepository, Request $request): Response
    {

        $forms = [];
        $pieces = $pieceRepository -> findAll(); 

        foreach($pieces as $piece) {
            $formIndex = $this->createForm(PieceType::class, $piece);
            $formIndex->handleRequest($request);
            $forms[$piece->getId()] = $formIndex->createView();
        }

        $newPiece = new Piece();
        $form = $this->createForm(PieceType::class, $newPiece);
        $form->handleRequest($request);
        return $this->render('piece/index.html.twig', [
            'pieces' => $pieces,
            'forms' => $forms,
            'form' => $form,
            'success' => $request->query->get('success'),

        ]);
    }

    #[Route('/new', name: 'app_piece_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $piece = new Piece();
        $form = $this->createForm(PieceType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compo = $form ->getData()->getCompositions();

            if(!$form->getData()->isFabrique()){
                $compo = [];
                $form->getData()->getGamme() == null;
            }
            else{
                foreach($compo as $c) {
                    $c->setPieceInter($piece);
                }
            }
            $entityManager->persist($piece);
            $entityManager->flush();
            $success =  "La pièce \"" .$piece -> getName()."\" a bien été ajoutée! ";
            return $this->redirectToRoute('app_piece_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->redirectToRoute('app_piece_index', [
            'success' => $success
        ]);
    }

    #[Route('/{id}', name: 'app_piece_show', methods: ['GET'])]
    public function show(Piece $piece): Response
    {
        return $this->render('piece/show.html.twig', [
            'piece' => $piece,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_piece_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Piece $piece, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PieceType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_piece_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('piece/edit.html.twig', [
            'piece' => $piece,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_piece_delete', methods: ['POST'])]
    public function delete(Request $request, Piece $piece, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$piece->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($piece);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_piece_index', [], Response::HTTP_SEE_OTHER);
    }
}
