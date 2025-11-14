<?php

namespace App\Products\ProductEntity;

use App\Products\ProductRepository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class ProductEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $naam = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prijs = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $soort = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ingredients = null;

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

    public function getPrijs(): ?string
    {
        return $this->prijs;
    }

    public function setPrijs(?string $prijs): static
    {
        $this->prijs = $prijs;

        return $this;
    }

    public function getSoort(): ?string
    {
        return $this->soort;
    }

    public function setSoort(?string $soort): static
    {
        $this->soort = $soort;

        return $this;
    }

    public function getIngredients(?string $ingredients): string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }
}
