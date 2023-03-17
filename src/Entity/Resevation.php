<?php

namespace App\Entity;
use App\Entity\Allergie;
use App\Entity\User;

use App\Repository\ResevationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResevationRepository::class)]
class Resevation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $time = null;

    #[ORM\Column]
    private ?int $numbre_of_people = null;

    #[ORM\ManyToOne(inversedBy: 'resevations')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Allergie::class, inversedBy: 'resevations')]
    private Collection $allergie;

    public function __construct()
    {
        $this->allergie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getNumbreOfPeople(): ?int
    {
        return $this->numbre_of_people;
    }

    public function setNumbreOfPeople(int $numbre_of_people): self
    {
        $this->numbre_of_people = $numbre_of_people;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Allergie>
     */
    public function getAllergie(): Collection
    {
        return $this->allergie;
    }

    public function addAllergie(Allergie $allergie): self
    {
        if (!$this->allergie->contains($allergie)) {
            $this->allergie->add($allergie);
        }

        return $this;
    }

    public function removeAllergie(Allergie $allergie): self
    {
        $this->allergie->removeElement($allergie);

        return $this;
    }
}
