<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateAjout = null;

    #[ORM\Column(length: 255)]
    private ?string $serialNumber = null;

    #[ORM\ManyToOne(targetEntity: Agence::class, inversedBy: 'materiels')]
    private ?Agence $agence = null;

    #[ORM\OneToMany(mappedBy: 'materiel', targetEntity: CRMateriel::class, cascade: ['persist', 'remove'])]
    private Collection $crMateriels;

    #[ORM\Column(type: 'integer')]
    private ?int $quantiteDisponible = 1;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    private ?Categorie $categorie = null;

    #[ORM\Column]
    private ?int $prix = null;

    public function __construct()
    {
        $this->crMateriels = new ArrayCollection();
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

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): static
    {
        $this->dateAjout = $dateAjout;
        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;
        return $this;
    }

    public function getCrMateriels(): Collection
    {
        return $this->crMateriels;
    }

    public function addCrMateriel(CRMateriel $crMateriel): static
    {
        if (!$this->crMateriels->contains($crMateriel)) {
            $this->crMateriels->add($crMateriel);
            $crMateriel->setMateriel($this);
        }
        return $this;
    }

    public function removeCrMateriel(CRMateriel $crMateriel): static
    {
        if ($this->crMateriels->removeElement($crMateriel)) {
            if ($crMateriel->getMateriel() === $this) {
                $crMateriel->setMateriel(null);
            }
        }
        return $this;
    }

    public function getQuantiteDisponible(): ?int
    {
        return $this->quantiteDisponible;
    }

    public function setQuantiteDisponible(int $quantiteDisponible): static
    {
        $this->quantiteDisponible = $quantiteDisponible;
        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;
        return $this;
    }
}
