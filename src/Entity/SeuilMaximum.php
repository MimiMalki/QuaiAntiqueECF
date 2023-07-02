<?php

namespace App\Entity;

use App\Repository\SeuilMaximumRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeuilMaximumRepository::class)]
class SeuilMaximum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbrSeatMax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrSeatMax(): ?int
    {
        return $this->nbrSeatMax;
    }

    public function setNbrSeatMax(int $nbrSeatMax): self
    {
        $this->nbrSeatMax = $nbrSeatMax;

        return $this;
    }
}
