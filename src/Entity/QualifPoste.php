<?php

namespace App\Entity;

use App\Repository\QualifPosteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualifPosteRepository::class)]
class QualifPoste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'qualifPostes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Poste $poste = null;

    #[ORM\ManyToOne(inversedBy: 'qualifPostes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usr = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsr(): ?user
    {
        return $this->usr;
    }

    public function setUsr(?user $usr): static
    {
        $this->usr = $usr;

        return $this;
    }
}
