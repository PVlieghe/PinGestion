<?php

namespace App\Entity;

use App\Repository\GammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GammeRepository::class)]
class Gamme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'gamme', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Piece $piece = null;

    #[ORM\ManyToOne(inversedBy: 'gammes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $referent = null;

    /**
     * @var Collection<int, CompoGamme>
     */
    #[ORM\OneToMany(targetEntity: CompoGamme::class, mappedBy: 'gamme', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $compoGammes;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    /**
     * @var Collection<int, Realisation>
     */
    #[ORM\OneToMany(targetEntity: Realisation::class, mappedBy: 'gamme')]
    private Collection $realisations;

    public function __construct()
    {
        $this->compoGammes = new ArrayCollection();
        $this->realisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPiece(): ?piece
    {
        return $this->piece;
    }

    public function setPiece(piece $piece): static
    {
        $this->piece = $piece;

        return $this;
    }

    public function getReferent(): ?user
    {
        return $this->referent;
    }

    public function setReferent(?user $referent): static
    {
        $this->referent = $referent;

        return $this;
    }

    /**
     * @return Collection<int, CompoGamme>
     */
    public function getCompoGammes(): Collection
    {
        return $this->compoGammes;
    }

    public function addCompoGamme(CompoGamme $compoGamme): static
    {
        if (!$this->compoGammes->contains($compoGamme)) {
            $this->compoGammes->add($compoGamme);
            $compoGamme->setGamme($this);
        }

        return $this;
    }

    public function removeCompoGamme(CompoGamme $compoGamme): static
    {
        if ($this->compoGammes->removeElement($compoGamme)) {
            // set the owning side to null (unless already changed)
            if ($compoGamme->getGamme() === $this) {
                $compoGamme->setGamme(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, Realisation>
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisation(Realisation $realisation): static
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations->add($realisation);
            $realisation->setGamme($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): static
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getGamme() === $this) {
                $realisation->setGamme(null);
            }
        }

        return $this;
    }
}
