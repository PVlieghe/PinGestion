<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PosteRepository::class)]
class Poste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, QualifPoste>
     */
    #[ORM\OneToMany(targetEntity: QualifPoste::class, mappedBy: 'poste', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $qualifPostes;

    /**
     * @var Collection<int, QualifMachine>
     */
    #[ORM\OneToMany(targetEntity: QualifMachine::class, mappedBy: 'poste', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $qualifMachines;

    /**
     * @var Collection<int, LigneReal>
     */
    #[ORM\OneToMany(targetEntity: LigneReal::class, mappedBy: 'poste')]
    private Collection $ligneReals;

    public function __construct()
    {
        $this->qualifPostes = new ArrayCollection();
        $this->qualifMachines = new ArrayCollection();
        $this->ligneReals = new ArrayCollection();
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

    /**
     * @return Collection<int, QualifPoste>
     */
    public function getQualifPostes(): Collection
    {
        return $this->qualifPostes;
    }

    public function addQualifPoste(QualifPoste $qualifPoste): static
    {
        if (!$this->qualifPostes->contains($qualifPoste)) {
            $this->qualifPostes->add($qualifPoste);
            $qualifPoste->setPoste($this);
        }

        return $this;
    }

    public function removeQualifPoste(QualifPoste $qualifPoste): static
    {
        if ($this->qualifPostes->removeElement($qualifPoste)) {
            // set the owning side to null (unless already changed)
            if ($qualifPoste->getPoste() === $this) {
                $qualifPoste->setPoste(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QualifMachine>
     */
    public function getQualifMachines(): Collection
    {
        return $this->qualifMachines;
    }

    public function addQualifMachine(QualifMachine $qualifMachine): static
    {
        if (!$this->qualifMachines->contains($qualifMachine)) {
            $this->qualifMachines->add($qualifMachine);
            $qualifMachine->setPoste($this);
        }

        return $this;
    }

    public function removeQualifMachine(QualifMachine $qualifMachine): static
    {
        if ($this->qualifMachines->removeElement($qualifMachine)) {
            // set the owning side to null (unless already changed)
            if ($qualifMachine->getPoste() === $this) {
                $qualifMachine->setPoste(null);
            }
        }

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
            $ligneReal->setPoste($this);
        }

        return $this;
    }

    public function removeLigneReal(LigneReal $ligneReal): static
    {
        if ($this->ligneReals->removeElement($ligneReal)) {
            // set the owning side to null (unless already changed)
            if ($ligneReal->getPoste() === $this) {
                $ligneReal->setPoste(null);
            }
        }

        return $this;
    }
}
