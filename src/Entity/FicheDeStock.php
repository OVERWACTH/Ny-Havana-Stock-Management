<?php

namespace App\Entity;

use App\Repository\FicheDeStockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheDeStockRepository::class)]
class FicheDeStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'ficheDeStocks')]
    private ?CR $numeroCR = null;

    #[ORM\ManyToOne(inversedBy: 'ficheDeStocks')]
    private ?Agence $demandeur = null;

    #[ORM\Column]
    private ?int $sortie = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    private ?string $signatureRH = null;

    #[ORM\Column(length: 255)]
    private ?string $signatureDemandeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNumeroCR(): ?CR
    {
        return $this->numeroCR;
    }

    public function setNumeroCR(?CR $numeroCR): static
    {
        $this->numeroCR = $numeroCR;

        return $this;
    }

    public function getDemandeur(): ?Agence
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Agence $demandeur): static
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getSortie(): ?int
    {
        return $this->sortie;
    }

    public function setSortie(int $sortie): static
    {
        $this->sortie = $sortie;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSignatureRH(): ?string
    {
        return $this->signatureRH;
    }

    public function setSignatureRH(string $signatureRH): static
    {
        $this->signatureRH = $signatureRH;

        return $this;
    }

    public function getSignatureDemandeur(): ?string
    {
        return $this->signatureDemandeur;
    }

    public function setSignatureDemandeur(string $signatureDemandeur): static
    {
        $this->signatureDemandeur = $signatureDemandeur;

        return $this;
    }
}
