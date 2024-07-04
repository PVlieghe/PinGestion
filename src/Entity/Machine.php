<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MachineRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Count;

#[ORM\Entity(repositoryClass: MachineRepository::class)]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    /**
     * @var Collection<int, QualifOperation>
     */
    #[ORM\OneToMany(targetEntity: QualifOperation::class, mappedBy: 'Machine', orphanRemoval: true, cascade:['persist', 'remove'])]
    private Collection $qualifOperations;

    /**
     * @var Collection<int, QualifMachine>
     */
    #[ORM\OneToMany(targetEntity: QualifMachine::class, mappedBy: 'machine', orphanRemoval: true, cascade:['persist', 'remove'])]
    #[Count(min : 1, minMessage : "Veuillez affilier au moins un poste à cette machine")]
    private Collection $qualifMachines;

    /**
     * @var Collection<int, LigneReal>
     */
    #[ORM\OneToMany(targetEntity: LigneReal::class, mappedBy: 'machine')]
    private Collection $ligneReals;


    public function __construct()
    {
        $this->qualifOperations = new ArrayCollection();
        $this->qualifMachines = new ArrayCollection();
        $this->ligneReals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, QualifOperation>
     */
    public function getQualifOperations(): Collection
    {
        return $this->qualifOperations;
    }

    public function addQualifOperation(QualifOperation $qualifOperation): static
    {
        if (!$this->qualifOperations->contains($qualifOperation)) {
            $this->qualifOperations->add($qualifOperation);
            $qualifOperation->setMachine($this);
        }

        return $this;
    }

    public function removeQualifOperation(QualifOperation $qualifOperation): static
    {
        if ($this->qualifOperations->removeElement($qualifOperation)) {
            // set the owning side to null (unless already changed)
            if ($qualifOperation->getMachine() === $this) {
                $qualifOperation->setMachine(null);
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
            $qualifMachine->setMachine($this);
        }

        return $this;
    }

    public function removeQualifMachine(QualifMachine $qualifMachine): static
    {
        if ($this->qualifMachines->removeElement($qualifMachine)) {
            // set the owning side to null (unless already changed)
            if ($qualifMachine->getMachine() === $this) {
                $qualifMachine->setMachine(null);
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
            $ligneReal->setMachine($this);
        }

        return $this;
    }

    public function removeLigneReal(LigneReal $ligneReal): static
    {
        if ($this->ligneReals->removeElement($ligneReal)) {
            // set the owning side to null (unless already changed)
            if ($ligneReal->getMachine() === $this) {
                $ligneReal->setMachine(null);
            }
        }

        return $this;
    }

    public function getQualifiedPostes(): ?Collection
    {
        $qualifs = $this->getQualifMachines();
        if($qualifs){
            $postes = [];
            foreach($qualifs as $qualif){
                $postes = $qualif->getPoste();
            }
        }

        else{
            return null;
        }

        return $postes;
    }
}
