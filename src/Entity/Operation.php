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

    public function __construct()
    {
        $this->compoGamme = new ArrayCollection();
        $this->qualifOperations = new ArrayCollection();
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

}
