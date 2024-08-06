<?php

namespace App\Controller;

use App\Entity\Reparation;
use App\Form\ReparationType;
use App\Repository\ReparationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reparation')]
class ReparationController extends AbstractController
{
    #[Route('/', name: 'app_reparation_index', methods: ['GET'])]
    public function index(ReparationRepository $reparationRepository): Response
    {
        return $this->render('reparation/index.html.twig', [
            'reparations' => $reparationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reparation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reparation = new Reparation();
        $form = $this->createForm(ReparationType::class, $reparation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reparation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reparation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reparation/new.html.twig', [
            'reparation' => $reparation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reparation_show', methods: ['GET'])]
    public function show(Reparation $reparation): Response
    {
        return $this->render('reparation/show.html.twig', [
            'reparation' => $reparation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reparation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reparation $reparation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReparationType::class, $reparation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reparation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reparation/edit.html.twig', [
            'reparation' => $reparation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reparation_delete', methods: ['POST'])]
    public function delete(Request $request, Reparation $reparation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reparation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reparation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reparation_index', [], Response::HTTP_SEE_OTHER);
    }
}
