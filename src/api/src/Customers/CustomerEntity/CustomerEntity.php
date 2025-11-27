<?php

namespace App\Customers\CustomerEntity;

use App\Customers\CustomerRepository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use App\Reservations\ReservationEntity\ReservationEntity;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class CustomerEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $naam;

    #[ORM\Column(length: 255)]
    private string $wachtwoord;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $telefoonnummer = null;

    #[OneToMany(mappedBy: "CustomerId", targetEntity: ReservationEntity::class)]
    private Collection $Reservations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): static
    {
        $this->naam = $naam;

        return $this;
    }

    public function getWachtwoord(): ?string
    {
        return $this->wachtwoord;
    }

    public function setWachtwoord(string $wachtwoord): static
    {
        $this->wachtwoord = $wachtwoord;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefoonnummer(): ?string
    {
        return $this->telefoonnummer;
    }

    public function setTelefoonnummer(?string $telefoonnummer): static
    {
        $this->telefoonnummer = $telefoonnummer;

        return $this;
    }

    public function getReservations(): Collection
    {
        return $this->Reservations;
    }
}
