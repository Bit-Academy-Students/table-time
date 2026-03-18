<?php

namespace App\Reservations\ReservationService;

/**
 * Bestandsnaam: ReservationService.php
 *
 * Beschrijving:
    * Dit bestand bevat de ReservationService klasse die verantwoordelijk is voor het afhandelen van HTTP-verzoeken
    * met betrekking tot Reservation-entiteiten in de applicatie. De service biedt methoden voor het ophalen,
    * aanmaken, bijwerken en verwijderen van Reservation via de ReservationRepository.
 *
 * Auteur: Johan Tol
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.20
 * - Laatste wijziging: 11 December 2025
 * - Beheer: Git
 */

use App\Reservations\ReservationEntity\ReservationEntity;
use App\Reservations\ReservationRepository\ReservationRepository;
use App\Restaurants\RestaurantRepository\RestaurantRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService 
{
    private ReservationRepository $ReservationRepository;
    private RestaurantRepository $RestaurantRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ReservationRepository $ReservationRepository,
        RestaurantRepository $RestaurantRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->ReservationRepository = $ReservationRepository;
        $this->RestaurantRepository = $RestaurantRepository;
        $this->entityManager = $entityManager;
    }

    private function sanitizeReservationData(array $data): array
    {
        if (isset($data['startDate']) && (!is_string($data['startDate']) || preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $data['startDate']) !== 1)) {
            throw new \InvalidArgumentException("Invalid input for start date");
        }
        if (isset($data['endDate']) && (!is_string($data['endDate']) || preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $data['endDate']) !== 1)) {
            throw new \InvalidArgumentException("Invalid input for end date");
        }
        $data['startDate'] = isset($data['startDate']) ? new DateTimeImmutable($data['startDate']) : null;
        $data['endDate'] = isset($data['endDate']) ? new DateTimeImmutable($data['endDate']) : null;
        return $data;
    }

    private function validateReservationData(array $data): void
    {
        if (empty($data['startDate']) || !($data['startDate'] instanceof \DateTimeInterface)) {
            throw new \InvalidArgumentException("Start date is required");
        }
        if (empty($data['endDate']) || !($data['endDate'] instanceof \DateTimeInterface)) {
            throw new \InvalidArgumentException("End date is required");
        }
        if ($data['startDate'] >= $data['endDate']) {
            throw new \InvalidArgumentException("Start date must be before end date");
        }
        if (empty($data['amountPeople']) || !is_int($data['amountPeople']) || $data['amountPeople'] <= 0) {
            throw new \InvalidArgumentException("Invalid input for amount of people");
        }
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }

        // BELANGRIJK: Check of restaurantId bestaat
        if (empty($data['restaurantId'])) {
            throw new \InvalidArgumentException("Restaurant ID is required");
        }

        // Haal restaurant op
        $restaurant = $this->RestaurantRepository->find($data['restaurantId']);
        if (!$restaurant) {
            throw new \InvalidArgumentException("Restaurant not found");
        }

        // Check overlapping reserveringen en capaciteit
        $reservations = $this->ReservationRepository->findAll();
        $maxCapacity = $restaurant->getMaxCapacity();
        $overlappingPeople = $data['amountPeople'];

        foreach ($reservations as $reservation) {
            // Check of reservering overlapt EN bij hetzelfde restaurant is
            $isOverlapping = (
                ($reservation->getStartDate() > $data['startDate'] && $reservation->getStartDate() < $data['endDate']) ||
                ($reservation->getEndDate() > $data['startDate'] && $reservation->getEndDate() < $data['endDate']) ||
                ($reservation->getStartDate() <= $data['startDate'] && $reservation->getEndDate() >= $data['endDate'])
            );

            $isSameRestaurant = $reservation->getRestaurant() && 
                                $reservation->getRestaurant()->getId() === $data['restaurantId'];

            if ($isOverlapping && $isSameRestaurant) {
                $overlappingPeople += $reservation->getAmountPeople();
            }
        }

        if ($overlappingPeople > $maxCapacity) {
            throw new \InvalidArgumentException("Maximum capacity exceeded for the selected time slot");
        }
    }

    public function getAllReservations(): array
    {
        return $this->ReservationRepository->findAll();
    }

    public function getReservationById(int $id): ?ReservationEntity
    {
        return $this->ReservationRepository->find($id);
    }

    public function createReservation(array $data): ReservationEntity
    {
        try {
            $data = $this->sanitizeReservationData($data);
            $this->validateReservationData($data);

            // BELANGRIJK: Haal het Restaurant Entity object op
            $restaurant = $this->RestaurantRepository->find($data['restaurantId']);
            if (!$restaurant) {
                throw new \InvalidArgumentException("Restaurant not found");
            }

            $Reservation = new ReservationEntity();
            $Reservation->setEmail($data['email']);
            $Reservation->setRestaurant($restaurant); // FIXED: Geef het hele restaurant object door!
            $Reservation->setStartDate($data['startDate']);
            $Reservation->setEndDate($data['endDate']);
            $Reservation->setAmountPeople($data['amountPeople']);

            $this->ReservationRepository->save($Reservation);

            return $Reservation;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }
    
    public function updateReservation(int $id, array $data): ?ReservationEntity
    {
        try {
            $data = $this->sanitizeReservationData($data);
            $Reservation = $this->ReservationRepository->find($id);
            
            if (!$Reservation) {
                return null;
            }

            // Update restaurant als restaurantId is gegeven
            if (isset($data['restaurantId'])) {
                $restaurant = $this->RestaurantRepository->find($data['restaurantId']);
                if (!$restaurant) {
                    throw new \InvalidArgumentException("Restaurant not found");
                }
                $Reservation->setRestaurant($restaurant);
            }

            if (isset($data['startDate'])) {
                $Reservation->setStartDate($data['startDate']);
            }
            if (isset($data['endDate'])) {
                $Reservation->setEndDate($data['endDate']);
            }
            if (isset($data['amountPeople'])) {
                $Reservation->setAmountPeople($data['amountPeople']);
            }
            if (isset($data['email'])) {
                $Reservation->setEmail($data['email']);
            }

            $this->ReservationRepository->save($Reservation);

            return $Reservation;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    public function deleteReservation(int $id): bool
    {
        $Reservation = $this->ReservationRepository->find($id);
        if (!$Reservation) {
            return false;
        }

        $this->ReservationRepository->remove($Reservation);

        return true;
    }

    // BONUS: Filter reserveringen op restaurant
    public function getReservationsByRestaurant(int $restaurantId): array
    {
        return $this->ReservationRepository->findBy(['RestaurantId' => $restaurantId]);
    }
}