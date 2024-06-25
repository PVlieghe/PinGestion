<?php

namespace App\Entity;

use App\Repository\CompoGammeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompoGammeRepository::class)]
class CompoGamme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'compoGammes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gamme $gamme = null;

    #[ORM\ManyToOne(inversedBy: 'compoGamme')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Operation $operation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamme(): ?gamme
    {
        return $this->gamme;
    }

    public function setGamme(?gamme $gamme): static
    {
        $this->gamme = $gamme;

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
