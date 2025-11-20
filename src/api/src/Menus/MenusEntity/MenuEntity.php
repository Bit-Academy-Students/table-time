<?php

namespace App\Menus\MenusEntity;

use App\Products\ProductsEntity\ProductsEntity;
use App\Restaurants\RestaurantEntity\RestaurantEntity;
use App\Menus\MenuRepository\MenuRepository;
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
    private ArrayCollection $ProductsIds;

    #[OneToOne(targetEntity: RestaurantEntity::class, inversedBy: "Menus")]
    #[JoinColumn(name: "RestaurantId", referencedColumnName: "id")]
    private RestaurantEntity $RestaurantId;

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
        return $this->ProductsIds;
    }

    public function setProductIds(ArrayCollection $ProductsIds): static
    {
        $this->ProductsIds = $ProductsIds;

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
