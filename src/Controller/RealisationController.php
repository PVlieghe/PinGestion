<?php

namespace App\Controller;

use RealisationType;
use App\Entity\Piece;


use App\Entity\Poste;
use App\Entity\LigneReal;
use RealisationPieceType;
use App\Entity\QualifPoste;
use App\Entity\Realisation;
use App\Form\LigneRealType;
use App\Entity\QualifMachine;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RealisationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('atelier/realisation')]
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
                foreach ($realisation->getLigneReals() as $key => $ligneReal) {
                    $selectedPiece ->setStock($selectedPiece ->getStock() + 1);
                    // Récupérez les valeurs des champs non mappés duree_hours et duree_minutes
                    $hours = $form->get('ligneReals')[$key]->get('duree_hours')->getData() ?? 0;
                    $minutes = $form->get('ligneReals')[$key]->get('duree_minutes')->getData() ?? 0;
                    
                    // Calculez la durée totale en minutes
                    $totalMinutes = ($hours * 60) + $minutes;
                    
                    // Définissez la durée en minutes
                    $ligneReal->setDuree($totalMinutes);
            
                    // Persistez chaque LigneReal si nécessaire
                    $entityManager->persist($ligneReal);
                }
            
                // Persistez la Realisation
                $entityManager->persist($realisation);
                $entityManager->flush();
                return $this->redirectToRoute('app_realisation_index', [
                    'success' => "Vous avez créé une réalisation pour la pièce: " . $realisation -> getGamme() ->getPiece() ->getLibelle() . "."
                ]);
            }
        }
        
    
        return $this->render('realisation/newSecond.html.twig', [
            'form' => $form,
            'operations' => $operations,
            'selectedPiece' => $selectedPiece,
        ]);
    }

}