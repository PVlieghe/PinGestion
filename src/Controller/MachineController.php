<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Form\MachineType;
use App\Repository\MachineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/machine')]
class MachineController extends AbstractController
{
    #[Route('/', name: 'app_machine_index', methods: ['GET'])]
    public function index(Request $request,  MachineRepository $machineRepository ): Response
    {
        
        $machines = $machineRepository -> findAll();

        $forms = [];

        foreach ($machines as $machine) {
            $formIndex = $this->createForm(MachineType::class, $machine);
            $formIndex->handleRequest($request);
            $forms[$machine->getId() ] = $formIndex->createView();
        }
        if(!$request->query->get('form')){
            $newMachine = new Machine();
            $form = $this->createForm(MachineType::class, $newMachine);
            $form->handleRequest($request);
        }
        else{
            $form = $request->query->get('form');
        }

        return $this->render('machine/index.html.twig', [
            'machines' => $machines,
            'form' => $form,
            'forms' => $forms,
            'success' => $request->query->get('success')
        ]);
    }

    #[Route('/new', name: 'app_machine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualifOpe = $form ->getData()->getQualifOperations();
            $qualifPoste = $form ->getData()->getQualifMachines();
            foreach($qualifOpe as $ope) {
                $ope->setMachine($machine);
            }
            foreach($qualifPoste as $poste) {
                $poste->setMachine($machine);
            }

            $entityManager->persist($machine);
            $entityManager->flush();
            $success =  "La machine \"" .$machine -> getName()."\" a bien été ajoutée! ";
        }

        return $this->redirectToRoute('app_machine_index', [
            'success' => $success,
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'app_machine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $success = null;
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualifOpe = $form ->getData()->getQualifOperations();
            $qualifPoste = $form ->getData()->getQualifMachines();
            foreach($qualifOpe as $ope) {
                $ope->setMachine($machine);
            }
            foreach($qualifPoste as $poste) {
                $poste->setMachine($machine);
            }

            $entityManager->persist($machine);
            $entityManager->flush();

            $success =  "La machine \"" .$machine->getName(). "\" a bien été modifiée!";
        }

        return $this->redirectToRoute('app_machine_index', [
            'success' => $success,
            
        ]);
    }

    #[Route('/{id}', name: 'app_machine_delete', methods: ['POST'])]
    public function delete(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $success = null;

        if ($this->isCsrfTokenValid('delete'.$machine->getId(), $request->getPayload()->getString('_token'))) {
            $success =  "La machine \"" .$machine->getName(). "\" a bien été supprimée!";
            $entityManager->remove($machine);
            $entityManager->flush();

        }

        return $this->redirectToRoute('app_machine_index', [
            'success' => $success
        ], Response::HTTP_SEE_OTHER);
    }
}
