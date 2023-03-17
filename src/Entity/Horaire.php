<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeStartM = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeEndM = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeStartN = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeEndN = null;

    #[ORM\Column(nullable: true)]
    private ?bool $closeM = null;

    #[ORM\Column(nullable: true)]
    private ?bool $closeN = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getTimeStartM(): ?\DateTimeInterface
    {
        return $this->timeStartM;
    }

    public function setTimeStartM(\DateTimeInterface $timeStartM): self
    {
        $this->timeStartM = $timeStartM;

        return $this;
    }

    public function getTimeEndM(): ?\DateTimeInterface
    {
        return $this->timeEndM;
    }

    public function setTimeEndM(\DateTimeInterface $timeEndM): self
    {
        $this->timeEndM = $timeEndM;

        return $this;
    }

    public function getTimeStartN(): ?\DateTimeInterface
    {
        return $this->timeStartN;
    }

    public function setTimeStartN(\DateTimeInterface $timeStartN): self
    {
        $this->timeStartN = $timeStartN;

        return $this;
    }

    public function getTimeEndN(): ?\DateTimeInterface
    {
        return $this->timeEndN;
    }

    public function setTimeEndN(\DateTimeInterface $timeEndN): self
    {
        $this->timeEndN = $timeEndN;

        return $this;
    }

    public function isCloseM(): ?bool
    {
        return $this->closeM;
    }

    public function setCloseM(?bool $closeM): self
    {
        $this->closeM = $closeM;

        return $this;
    }

    public function isCloseN(): ?bool
    {
        return $this->closeN;
    }

    public function setCloseN(?bool $closeN): self
    {
        $this->closeN = $closeN;

        return $this;
    }
}
