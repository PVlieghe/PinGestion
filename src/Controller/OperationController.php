<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\CompoGamme;
use App\Entity\QualifOperation;
use App\Form\OperationType;
use App\Form\OpeToGammeType;
use App\Form\OpeToMachineType;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('atelier/operation')]
class OperationController extends AbstractController
{
    #[Route('/', name: 'app_operation_index', methods: ['GET'])]
    public function index(Request $request,  OperationRepository $operationRepository ): Response
    {
        
        $operations = $operationRepository -> findAll();

        $forms = [];

        foreach ($operations as $operation) {
            $formIndex = $this->createForm(OperationType::class, $operation);
            $formIndex->handleRequest($request);
            $forms[$operation->getId() ] = $formIndex->createView();
        }

        $newOperation = new Operation();
        $form = $this->createForm(OperationType::class, $newOperation);
        $form->handleRequest($request);

        return $this->render('operation/index.html.twig', [
            'operations' => $operations,
            'form' => $form,
            'forms' => $forms,
            'success' => $request->query->get('success')
        ]);
    }

    #[Route('/new', name: 'app_operation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compoData = $form ->getData()->getCompoGamme();
            $machineData = $form ->getData()->getQualifOperations();
            foreach($compoData as $compo) {
                $compo->setOperation($operation);
            }
            foreach($machineData as $machine) {
                $machine->setOperation($operation);
            }

            $entityManager->persist($operation);
            $entityManager->flush();
            $success =  "L'opération \"" .$operation -> getContent()."\" a bien été ajoutée! ";
        }

        return $this->redirectToRoute('app_operation_index', [
            'success' => $success
        ]);
    }

    #[Route('/{id}', name: 'app_operation_show', methods: ['GET'])]
    public function show(Operation $operation): Response
    {
        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_operation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compoData = $form ->getData()->getCompoGamme();
            $machineData = $form ->getData()->getQualifOperations();
            foreach($compoData as $compo) {
                $compo->setOperation($operation);
            }
            foreach($machineData as $machine) {
                $machine->setOperation($operation);
            }

            $entityManager->persist($operation);
            $entityManager->flush();

            $success =  "L'opération \"" .$operation->getContent(). "\" a bien été modifiée!";
        }

        return $this->redirectToRoute('app_operation_index', [
            'success' => $success
        ]);
    }

    #[Route('/{id}', name: 'app_operation_delete', methods: ['POST'])]
    public function delete(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($operation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
    }
}
