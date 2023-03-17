<?php

namespace App\Entity;

use App\Repository\AllergieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllergieRepository::class)]
class Allergie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'allergies')]
    private Collection $user;

    #[ORM\ManyToMany(targetEntity: Resevation::class, mappedBy: 'allergie')]
    private Collection $resevations;

    #[ORM\ManyToMany(targetEntity: Reservation::class, mappedBy: 'allergie')]
    private Collection $reservations;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->resevations = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    

    /**
     * @return Collection<int, Resevation>
     */
    public function getResevations(): Collection
    {
        return $this->resevations;
    }

    public function addResevation(Resevation $resevation): self
    {
        if (!$this->resevations->contains($resevation)) {
            $this->resevations->add($resevation);
            $resevation->addAllergie($this);
        }

        return $this;
    }

    public function removeResevation(Resevation $resevation): self
    {
        if ($this->resevations->removeElement($resevation)) {
            $resevation->removeAllergie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->addAllergie($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            $reservation->removeAllergie($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }
}
