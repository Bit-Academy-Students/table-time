<!--
/**
 * Bestandsnaam: ReservationEntity.php
 *
 * Beschrijving:
    * Dit bestand bevat de ReservationEntity klasse die de Reservation-entiteit in de applicatie definieert.
    * De entiteit bevat eigenschappen zoals id, naam, prijs, soort, ingredients en de relatie met Menu-entiteiten.
 *
 * Auteur: Johan Tol
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.6
 * - Laatste wijziging: 28 November 2025
 * - Beheer: Git
 */
-->

<?php

namespace App\Reservations\ReservationEntity;

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

    #[ORM\Column(length: 255)]
    private string $Email;

    #[ManyToOne(targetEntity: RestaurantEntity::class, inversedBy: "Reservations")]
    #[JoinColumn(name: "RestaurantId", referencedColumnName: "id")]
    private ?RestaurantEntity $RestaurantId;

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
    
    public function getEmail(): string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getRestaurant(): ?RestaurantEntity
    {
        return $this->RestaurantId;
    }

    public function setRestaurant(?RestaurantEntity $RestaurantId): static
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
