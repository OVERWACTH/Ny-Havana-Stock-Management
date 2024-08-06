<?php

// src/Entity/CRMateriel.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CRMaterielRepository;


#[ORM\Entity(repositoryClass: CRMaterielRepository::class)]
class CRMateriel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CR::class, inversedBy: 'crMateriels')]
    private ?CR $cr = null;

    #[ORM\ManyToOne(targetEntity: Materiel::class)]
    private ?Materiel $materiel = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCr(): ?CR
    {
        return $this->cr;
    }

    public function setCr(?CR $cr): static
    {
        $this->cr = $cr;

        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): static
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }
}
