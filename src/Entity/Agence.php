<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    /**
     * @var Collection<int, Materiel>
     */
    #[ORM\OneToMany(targetEntity: Materiel::class, mappedBy: 'agence')]
    private Collection $materiels;

    /**
     * @var Collection<int, Reparation>
     */
    #[ORM\OneToMany(targetEntity: Reparation::class, mappedBy: 'direction')]
    private Collection $reparations;

    /**
     * @var Collection<int, FicheDeStock>
     */
    #[ORM\OneToMany(targetEntity: FicheDeStock::class, mappedBy: 'demandeur')]
    private Collection $ficheDeStocks;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
        $this->reparations = new ArrayCollection();
        $this->ficheDeStocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->setAgence($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getAgence() === $this) {
                $materiel->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reparation>
     */
    public function getReparations(): Collection
    {
        return $this->reparations;
    }

    public function addReparation(Reparation $reparation): static
    {
        if (!$this->reparations->contains($reparation)) {
            $this->reparations->add($reparation);
            $reparation->setDirection($this);
        }

        return $this;
    }

    public function removeReparation(Reparation $reparation): static
    {
        if ($this->reparations->removeElement($reparation)) {
            // set the owning side to null (unless already changed)
            if ($reparation->getDirection() === $this) {
                $reparation->setDirection(null);
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
            $ficheDeStock->setDemandeur($this);
        }

        return $this;
    }

    public function removeFicheDeStock(FicheDeStock $ficheDeStock): static
    {
        if ($this->ficheDeStocks->removeElement($ficheDeStock)) {
            // set the owning side to null (unless already changed)
            if ($ficheDeStock->getDemandeur() === $this) {
                $ficheDeStock->setDemandeur(null);
            }
        }

        return $this;
    }
}
