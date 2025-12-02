<?php

namespace App\Restaurants\RestaurantEntity;

use App\Restaurants\RestaurantRepository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use App\Reservations\ReservationEntity\ReservationEntity;
use App\Menus\MenusEntity\MenuEntity;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class RestaurantEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $naam = null;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $wachtwoord;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $locatie = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $telefoonnummer = null;

    #[ORM\Column]
    private int $maxCapacity = 50;

    #[OneToMany(mappedBy: "RestaurantId", targetEntity: ReservationEntity::class)]
    private Collection $Reservations;
    
    #[OneToOne(mappedBy: "RestaurantId", targetEntity: MenuEntity::class)]
    private Collection $Menu;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    public function getLocatie(): ?string
    {
        return $this->locatie;
    }

    public function setLocatie(?string $locatie): static
    {
        $this->locatie = $locatie;

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

    public function getMaxCapacity(): int
    {
        return $this->maxCapacity;
    }

    public function setMaxCapacity(int $maxCapacity): static
    {
        $this->maxCapacity = $maxCapacity;

        return $this;
    }

    public function getReservations(): Collection
    {
        return $this->Reservations;
    }

    public function getMenu(): Collection
    {
        return $this->Menu;
    }

    public function setMenu(Collection $Menu): static
    {
        $this->Menu = $Menu;

        return $this;
    }
}
