<?php

namespace App\Entity;

use App\Repository\CompositionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompositionRepository::class)]
class Composition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'compositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Piece $pieceFabrique = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Piece $pieceInter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPieceFabrique(): ?Piece
    {
        return $this->pieceFabrique;
    }

    public function setPieceFabrique(?Piece $pieceFabrique): static
    {
        $this->pieceFabrique = $pieceFabrique;

        return $this;
    }

    public function getPieceInter(): ?Piece
    {
        return $this->pieceInter;
    }

    public function setPieceInter(?Piece $pieceInter): static
    {
        $this->pieceInter = $pieceInter;

        return $this;
    }
}
