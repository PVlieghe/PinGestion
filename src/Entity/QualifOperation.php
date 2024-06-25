<?php

namespace App\Entity;

use App\Repository\QualifOperationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualifOperationRepository::class)]
class QualifOperation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'qualifOperations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Machine $Machine = null;

    #[ORM\ManyToOne(inversedBy: 'qualifOperations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Operation $operation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMachine(): ?Machine
    {
        return $this->Machine;
    }

    public function setMachine(?Machine $Machine): static
    {
        $this->Machine = $Machine;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): static
    {
        $this->operation = $operation;

        return $this;
    }
}
