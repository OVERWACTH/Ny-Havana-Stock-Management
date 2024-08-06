<?php 
// src/Entity/CR.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CRRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CRRepository::class)]
class CR
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $numeroCR = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(length: 255)]
    private ?string $nomDemandeur = null;

    #[ORM\OneToMany(mappedBy: 'cr', targetEntity: CRMateriel::class, cascade: ['persist', 'remove'])]
    private Collection $crMateriels;

    /**
     * @var Collection<int, FicheDeStock>
     */
    #[ORM\OneToMany(targetEntity: FicheDeStock::class, mappedBy: 'numeroCR')]
    private Collection $ficheDeStocks;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function __construct()
    {
        $this->crMateriels = new ArrayCollection();
        $this->ficheDeStocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCR(): ?int
    {
        return $this->numeroCR;
    }

    public function setNumeroCR(int $numeroCR): static
    {
        $this->numeroCR = $numeroCR;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): static
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getNomDemandeur(): ?string
    {
        return $this->nomDemandeur;
    }

    public function setNomDemandeur(string $nomDemandeur): static
    {
        $this->nomDemandeur = $nomDemandeur;

        return $this;
    }

    /**
     * @return Collection<int, CRMateriel>
     */
    public function getCrMateriels(): Collection
    {
        return $this->crMateriels;
    }

    public function addCrMateriel(CRMateriel $crMateriel): static
    {
        if (!$this->crMateriels->contains($crMateriel)) {
            $this->crMateriels->add($crMateriel);
            $crMateriel->setCr($this);
        }

        return $this;
    }

    public function removeCrMateriel(CRMateriel $crMateriel): static
    {
        if ($this->crMateriels->removeElement($crMateriel)) {
            if ($crMateriel->getCr() === $this) {
                $crMateriel->setCr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheDeStock>
     */
    public function getFicheDeStocks(): Collection
    {
        return $this->ficheDeStocks;
    }

    public function addFicheDeStock(FicheDeStock $ficheDeStock): static
    {
        if (!$this->ficheDeStocks->contains($ficheDeStock)) {
            $this->ficheDeStocks->add($ficheDeStock);
            $ficheDeStock->setNumeroCR($this);
        }

        return $this;
    }

    public function removeFicheDeStock(FicheDeStock $ficheDeStock): static
    {
        if ($this->ficheDeStocks->removeElement($ficheDeStock)) {
            // set the owning side to null (unless already changed)
            if ($ficheDeStock->getNumeroCR() === $this) {
                $ficheDeStock->setNumeroCR(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
