<?php 
// src/Service/CRNumberGenerator.php

namespace App\Service;

use App\Repository\CRRepository;

class CRNumberGenerator
{
    private $crRepository;

    public function __construct(CRRepository $crRepository)
    {
        $this->crRepository = $crRepository;
    }

    public function generateNewCRNumber(): int
    {
        // Vous pouvez ajouter une logique pour calculer le nouveau numéro CR ici
        // Exemple simple: trouver le plus grand numéro CR existant et ajouter 1
        $lastCR = $this->crRepository->findOneBy([], ['numeroCR' => 'DESC']);
        return $lastCR ? $lastCR->getNumeroCR() + 1 : 60000;
    }
}
