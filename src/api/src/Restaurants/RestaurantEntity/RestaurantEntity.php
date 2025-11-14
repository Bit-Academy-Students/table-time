<?php

namespace App\Restaurants\RestaurantEntity;

use App\Restaurants\RestaurantRepository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class RestaurantEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $naam = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $locatie = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $telefoonnummer = null;

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
}
