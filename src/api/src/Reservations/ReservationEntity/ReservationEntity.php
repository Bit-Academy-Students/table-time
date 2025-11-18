<?php

namespace App\Reservations\ReservationEntity;

use App\Customers\CustomerEntity\CustomerEntity;
use App\Restaurants\RestaurantEntity\RestaurantEntity;
use App\Reservations\ReservationRepository\ReservationRepository;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class ReservationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ManyToOne(targetEntity: CustomerEntity::class, inversedBy: "Reservations")]
    #[JoinColumn(name: "CustomerId", referencedColumnName: "id")]
    private CustomerEntity $CustomerId;

    #[ManyToOne(targetEntity: RestaurantEntity::class, inversedBy: "Reservations")]
    #[JoinColumn(name: "RestaurantId", referencedColumnName: "id")]
    private RestaurantEntity $RestaurantId;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $StartDate;
    
    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $EndDate;

    #[ORM\Column]
    private int $AmountPeople;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
    
    public function getCustomerId(): CustomerEntity
    {
        return $this->CustomerId;
    }

    public function setCustomerId(CustomerEntity $CustomerId): static
    {
        $this->CustomerId = $CustomerId;

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

    public function getStartDate(): DateTimeInterface
    {
        return $this->StartDate;
    }

    public function setStartDate(DateTimeInterface $StartDate): static
    {
        $this->StartDate = $StartDate;

        return $this;
    }

    public function getEndDate(): DateTimeInterface
    {
        return $this->EndDate;
    }

    public function setEndDate(DateTimeInterface $EndDate): static
    {
        $this->EndDate = $EndDate;

        return $this;
    }

    public function getAmountPeople(): int
    {
        return $this->AmountPeople;
    }

    public function setAmountPeople(int $AmountPeople): static
    {
        $this->AmountPeople = $AmountPeople;

        return $this;
    }
}
