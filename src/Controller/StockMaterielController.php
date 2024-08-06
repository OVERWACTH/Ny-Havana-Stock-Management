<?php

namespace App\Controller;

use App\Repository\StockMaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StockMaterielController extends AbstractController
{
    #[Route('/stock', name: 'app_stock_index', methods: ['GET'])]
    public function index(StockMaterielRepository $stockMaterielRepository): Response
    {
        $stocks = $stockMaterielRepository->findAll();
    
        return $this->render('stock/index.html.twig', [
            'stocks' => $stocks,
        ]);
    }
}
