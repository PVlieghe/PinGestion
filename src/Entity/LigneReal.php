<?php

namespace App\Entity;

use App\Repository\LigneRealRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneRealRepository::class)]
class LigneReal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneReals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Operation $operation = null;

    #[ORM\ManyToOne(inversedBy: 'ligneReals')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Machine $machine = null;

    #[ORM\ManyToOne(inversedBy: 'ligneReals')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Poste $poste = null;


    #[ORM\ManyToOne(inversedBy: 'ligneReals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Realisation $realisation = null;

    #[ORM\Column]
    private ?int $duree = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMachine(): ?Machine
    {
        return $this->machine;
    }

    public function setMachine(?Machine $machine): static
    {
        $this->machine = $machine;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): static
    {
        $this->poste = $poste;

        return $this;
    }


    public function getRealisation(): ?Realisation
    {
        return $this->realisation;
    }

    public function setRealisation(?Realisation $realisation): static
    {
        $this->realisation = $realisation;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getWrittenDuree(): string
    {
        $hours = intdiv($this->getDuree(), 60);
        $minutes = $this->getDuree() % 60;
        return sprintf('%d h %d mn', $hours, $minutes);
    }
}
