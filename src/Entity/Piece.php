<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PieceRepository::class)]
class Piece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_vente = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_achat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $intermediaire = null;

    #[ORM\Column(nullable: true)]
    private ?bool $fabrique = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\OneToOne(mappedBy: 'piece', cascade: ['persist', 'remove'])]
    private ?Gamme $gamme = null;


    /**
     * @var Collection<int, Composition>
     */
    #[ORM\OneToMany(targetEntity: Composition::class, mappedBy: 'pieceFabrique',  orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $compositions;

    public function __construct()
    {

        $this->compositions = new ArrayCollection();
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prix_vente;
    }

    public function setPrixVente(?float $prix_vente): static
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prix_achat;
    }

    public function setPrixAchat(float $prix_achat): static
    {
        $this->prix_achat = $prix_achat;

        return $this;
    }

    public function isIntermediaire(): ?bool
    {
        return $this->intermediaire;
    }

    public function setIntermediaire(bool $intermediaire): static
    {
        $this->intermediaire = $intermediaire;

        return $this;
    }

    public function isFabrique(): ?bool
    {
        return $this->fabrique;
    }

    public function setFabrique(bool $fabrique): static
    {
        $this->fabrique = $fabrique;

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

    public function getGamme(): ?Gamme
    {
        return $this->gamme;
    }

    public function setGamme(Gamme $gamme): static
    {
        // set the owning side of the relation if necessary
        if ($gamme->getPiece() !== $this) {
            $gamme->setPiece($this);
        }

        $this->gamme = $gamme;

        return $this;
    }


    /**
     * @return Collection<int, Composition>
     */
    public function getCompositions(): Collection
    {
        return $this->compositions;
    }

    public function addComposition(Composition $composition): static
    {
        if (!$this->compositions->contains($composition)) {
            $this->compositions->add($composition);
            $composition->setPieceFabrique($this);
        }

        return $this;
    }

    public function removeComposition(Composition $composition): static
    {
        if ($this->compositions->removeElement($composition)) {
            // set the owning side to null (unless already changed)
            if ($composition->getPieceFabrique() === $this) {
                $composition->setPieceFabrique(null);
            }
        }

        return $this;
    }
}
