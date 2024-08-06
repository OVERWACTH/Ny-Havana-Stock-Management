<?php

namespace App\Controller;

use App\Entity\FicheDeStock;
use App\Form\FicheDeStockType;
use App\Repository\FicheDeStockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fiche/de/stock')]
class FicheDeStockController extends AbstractController
{
    #[Route('/', name: 'app_fiche_de_stock_index', methods: ['GET'])]
    public function index(FicheDeStockRepository $ficheDeStockRepository): Response
    {
        return $this->render('fiche_de_stock/index.html.twig', [
            'fiche_de_stocks' => $ficheDeStockRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fiche_de_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ficheDeStock = new FicheDeStock();
        $form = $this->createForm(FicheDeStockType::class, $ficheDeStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ficheDeStock);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_de_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_de_stock/new.html.twig', [
            'fiche_de_stock' => $ficheDeStock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_de_stock_show', methods: ['GET'])]
    public function show(FicheDeStock $ficheDeStock): Response
    {
        return $this->render('fiche_de_stock/show.html.twig', [
            'fiche_de_stock' => $ficheDeStock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_de_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheDeStock $ficheDeStock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FicheDeStockType::class, $ficheDeStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_de_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_de_stock/edit.html.twig', [
            'fiche_de_stock' => $ficheDeStock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_de_stock_delete', methods: ['POST'])]
    public function delete(Request $request, FicheDeStock $ficheDeStock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheDeStock->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ficheDeStock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fiche_de_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
