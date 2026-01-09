<!--
/**
 * Bestandsnaam: ProductsEntity.php
 *
 * Beschrijving:
    * Dit bestand bevat de ProductsEntity klasse die de Products-entiteit in de applicatie definieert.
    * De entiteit bevat eigenschappen zoals id, naam, prijs, soort, ingredients en de relatie met Menu-entiteiten.
 *
 * Auteur: Keano Broekman
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.4
 * - Laatste wijziging: 25 November 2025
 * - Beheer: Git
 */
-->


<?php

namespace App\Products\ProductsEntity;

use App\Products\ProductsRepository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use App\Menus\MenusEntity\MenuEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class ProductsEntity
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
    
    #[ManyToMany(mappedBy: "ProductIds", targetEntity: MenuEntity::class)]
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
