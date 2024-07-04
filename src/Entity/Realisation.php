<?php

namespace App\Entity;

use App\Repository\RealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealisationRepository::class)]
class Realisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'realisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gamme $gamme = null;

    /**
     * @var Collection<int, LigneReal>
     */
    #[ORM\OneToMany(targetEntity: LigneReal::class, mappedBy: 'realisation', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $ligneReals;

    #[ORM\ManyToOne(inversedBy: 'realisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ouvrier = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->ligneReals = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamme(): ?Gamme
    {
        return $this->gamme;
    }

    public function setGamme(?Gamme $gamme): static
    {
        $this->gamme = $gamme;

        return $this;
    }

    /**
     * @return Collection<int, LigneReal>
     */
    public function getLigneReals(): Collection
    {
        return $this->ligneReals;
    }

    public function addLigneReal(LigneReal $ligneReal): static
    {
        if (!$this->ligneReals->contains($ligneReal)) {
            $this->ligneReals->add($ligneReal);
            $ligneReal->setRealisation($this);
        }

        return $this;
    }

    public function removeLigneReal(LigneReal $ligneReal): static
    {
        if ($this->ligneReals->removeElement($ligneReal)) {
            // set the owning side to null (unless already changed)
            if ($ligneReal->getRealisation() === $this) {
                $ligneReal->setRealisation(null);
            }
        }

        return $this;
    }

    public function getOuvrier(): ?User
    {
        return $this->ouvrier;
    }

    public function setOuvrier(?User $ouvrier): static
    {
        $this->ouvrier = $ouvrier;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
