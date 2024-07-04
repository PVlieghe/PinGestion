<?php

namespace App\Controller;

use RealisationType;
use App\Entity\Piece;


use App\Entity\Poste;
use App\Entity\LigneReal;
use RealisationPieceType;
use App\Entity\Realisation;
use App\Form\LigneRealType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RealisationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/realisation')]
class RealisationController extends AbstractController
{
    #[Route('/', name: 'app_realisation_index', methods: ['GET'])]
    public function index(Request $request,  RealisationRepository $realisationRepository ): Response
    {
        
        $reals = $realisationRepository -> findAll();

        return $this->render('realisation/index.html.twig', [
            'reals' => $reals,
            'success' => $request->query->get('success')
        ]);
    }

    #[Route('/newFirst', name: 'app_realisation_new_first', methods: ['GET', 'POST'])]
    public function newFirst(Request $request): Response
    {
        $selectedPiece = null;
        $form = $this->createForm(RealisationPieceType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $selectedPiece = $data['piece'];

            
            return $this->redirectToRoute('app_realisation_new_second', [
                'id' => $selectedPiece->getId() // Assurez-vous de passer l'ID de la pièce
            ]);
        }
    
        return $this->render('realisation/newFirst.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/newSecond/{id}', name: 'app_realisation_new_second', methods: ['GET', 'POST'])]
    public function newSecond(Request $request, Piece $selectedPiece, Security $security, EntityManagerInterface $entityManager): Response
    {

        //Récupération des opérations associées à la pièce.
        $operations = [];
        if ($selectedPiece) {
            
            $compoGammes = $selectedPiece->getGamme()->getCompoGammes();
            foreach ($compoGammes as $compoGamme) {
                    
                    $operations[] = $compoGamme->getOperation();
                
            }
            
            $realisation = new Realisation();
            $realisation ->setGamme($selectedPiece->getGamme());
            $realisation ->setOuvrier($security->getUser());
    
            // Pré-remplir les LigneReal pour chaque opération
            foreach ($operations as $operation) {
                $ligneReal = new LigneReal();
                $ligneReal->setOperation($operation);
                $realisation->addLigneReal($ligneReal);
            }

            $form = $this->createForm(RealisationType::class, $realisation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                foreach ($realisation->getLigneReals() as $ligneReal) {

            

            
                    // Persistez chaque LigneReal si nécessaire
                    $entityManager->persist($ligneReal);
                }
            
                // Persistez la Realisation complète si nécessaire
                $entityManager->persist($realisation);
                $entityManager->flush();
            
                return $this->redirectToRoute('app_realisation_index', [
                    $success = "Vous avez créé une réalisation pour la pièce: " . $realisation -> getGamme() ->getPiece() ->getLibelle() . "."
                ]);
            }
        }
        
    
        return $this->render('realisation/newSecond.html.twig', [
            'form' => $form,
            'selectedPiece' => $selectedPiece,
        ]);
    }

    #[Route('/{id}', name: 'app_realisation_delete', methods: ['POST'])]
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
