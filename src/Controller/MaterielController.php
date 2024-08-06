<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Entity\StockMateriel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use App\Repository\StockMaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/materiel')]
class MaterielController extends AbstractController
{
    #[Route('/', name: 'app_materiel_index', methods: ['GET'])]
    public function index(MaterielRepository $materielRepository): Response
    {
        return $this->render('materiel/index.html.twig', [
            'materiels' => $materielRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, StockMaterielRepository $stockMaterielRepository): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materielName = $materiel->getName();

            // Rechercher si un stock pour le matériel avec le même nom existe déjà
            $stockMateriel = $stockMaterielRepository->findOneBy(['name' => $materielName]);

            if ($stockMateriel) {
                // Incrémenter la quantité disponible
                $stockMateriel->setQuantiteDisponible($stockMateriel->getQuantiteDisponible() + 1);
            } else {
                // Créer un nouveau stock pour ce matériel
                $stockMateriel = new StockMateriel();
                $stockMateriel->setName($materielName);
                $stockMateriel->setQuantiteDisponible(1);
                $entityManager->persist($stockMateriel);
            }

            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_show', methods: ['GET'])]
    public function show(Materiel $materiel): Response
    {
        return $this->render('materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            // Mise à jour de la quantité disponible avant de supprimer le matériel
            $stockMateriel = $entityManager->getRepository(StockMateriel::class)->findOneBy(['name' => $materiel->getName()]);
            if ($stockMateriel) {
                $stockMateriel->setQuantiteDisponible($stockMateriel->getQuantiteDisponible() - 1);
                if ($stockMateriel->getQuantiteDisponible() <= 0) {
                    $entityManager->remove($stockMateriel);
                }
            }

            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
    }
}
