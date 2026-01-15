<?php

namespace App\Menus\MenusEntity;

/**
 * Bestandsnaam: MenuEntity.php
 *
 * Beschrijving:
    * Dit bestand bevat de MenuEntity klasse die de structuur en relaties van een menu in de applicatie definieert.
 *
 * Auteur: Johan Tol
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.6
 * - Laatste wijziging: 4 December 2025
 * - Beheer: Git
 */

use App\Products\ProductsEntity\ProductsEntity;
use App\Restaurants\RestaurantEntity\RestaurantEntity;
use App\Menus\MenusRepository\MenuRepository;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class MenuEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ManyToMany(targetEntity: ProductsEntity::class, inversedBy: "Menus")]
    #[JoinColumn(name: "ProductsId", referencedColumnName: "id")]
    private ArrayCollection $ProductIds;

    #[OneToOne(targetEntity: RestaurantEntity::class, inversedBy: "Menus")]
    #[JoinColumn(name: "RestaurantId", referencedColumnName: "id")]
    private RestaurantEntity $RestaurantId;

    function __construct()
    {
        $this->ProductIds = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
    
    public function getProductIds(): ArrayCollection
    {
        return $this->ProductIds;
    }

    public function setProductIds(ArrayCollection $ProductIds): static
    {
        $this->ProductIds = $ProductIds;

        return $this;
    }

    public function getRestaurantId(): RestaurantEntity
    {
        return $this->RestaurantId;
    }

    public function setRestaurantId(RestaurantEntity $RestaurantId): static
    {
        $this->RestaurantId = $RestaurantId;

        return $this;
    }
}
