<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private Collection $qualifMachines;


    public function __construct()
    {
        $this->qualifOperations = new ArrayCollection();
        $this->qualifMachines = new ArrayCollection();
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
}
