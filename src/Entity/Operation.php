<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    /**
     * @var Collection<int, CompoGamme>
     */
    #[ORM\OneToMany(targetEntity: CompoGamme::class, mappedBy: 'operation', orphanRemoval: true, cascade:['persist', 'remove'])]
    private Collection $compoGamme;


    /**
     * @var Collection<int, QualifOperation>
     */
    #[ORM\OneToMany(targetEntity: QualifOperation::class, mappedBy: 'operation', orphanRemoval: true,  cascade:['persist', 'remove'])]
    private Collection $qualifOperations;

    /**
     * @var Collection<int, LigneReal>
     */
    #[ORM\OneToMany(targetEntity: LigneReal::class, mappedBy: 'operation')]
    private Collection $ligneReals;

    public function __construct()
    {
        $this->compoGamme = new ArrayCollection();
        $this->qualifOperations = new ArrayCollection();
        $this->ligneReals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, CompoGamme>
     */
    public function getCompoGamme(): Collection
    {
        return $this->compoGamme;
    }

    public function addCompoGamme(CompoGamme $compoGamme): static
    {
        if (!$this->compoGamme->contains($compoGamme)) {
            $this->compoGamme->add($compoGamme);
            $compoGamme->setOperation($this);
        }

        return $this;
    }

    public function removeCompoGamme(CompoGamme $compoGamme): static
    {
        if ($this->compoGamme->removeElement($compoGamme)) {
            // set the owning side to null (unless already changed)
            if ($compoGamme->getOperation() === $this) {
                $compoGamme->setOperation(null);
            }
        }

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
            $qualifOperation->setOperation($this);
        }

        return $this;
    }

    public function removeQualifOperation(QualifOperation $qualifOperation): static
    {
        if ($this->qualifOperations->removeElement($qualifOperation)) {
            // set the owning side to null (unless already changed)
            if ($qualifOperation->getOperation() === $this) {
                $qualifOperation->setOperation(null);
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
            $ligneReal->setOperation($this);
        }

        return $this;
    }

    public function removeLigneReal(LigneReal $ligneReal): static
    {
        if ($this->ligneReals->removeElement($ligneReal)) {
            // set the owning side to null (unless already changed)
            if ($ligneReal->getOperation() === $this) {
                $ligneReal->setOperation(null);
            }
        }

        return $this;
    }

    public function getQualifiedMachines(): ?Collection
    {
        $qualifs = $this->getQualifOperations();
        if($qualifs){
            $machines = [];
            foreach($qualifs as $qualif){
                $machines = $qualif->getMachine();
            }
        }

        else{
            return null;
        }

        return $machines;
    }

}
