<?php

namespace App\Reservations\ReservationService;

use App\Reservations\ReservationEntity\ReservationEntity;
use App\Reservations\ReservationRepository\ReservationRepository;
use App\Customers\CustomerEntity\CustomerEntity;
use App\Restaurants\RestaurantEntity\RestaurantEntity;

class ReservationService 
{
    private ReservationRepository $ReservationRepository;

    public function __construct(ReservationRepository $ReservationRepository)
    {
        $this->ReservationRepository = $ReservationRepository;
    }

    private function sanitizeReservationData(array $data): array
    {
        if (isset($data['startDate']) && (!is_string($data['startDate']) || preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $data['startDate']) !== 1)) {
            throw new \InvalidArgumentException("Invalid input for start date");
        }
        if (isset($data['endDate']) && (!is_string($data['endDate']) || preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $data['endDate']) !== 1)) {
            throw new \InvalidArgumentException("Invalid input for end date");
        }
        $data['startDate'] = isset($data['startDate']) ? new \DateTimeImmutable($data['startDate']) : null;
        $data['endDate'] = isset($data['endDate']) ? new \DateTimeImmutable($data['endDate']) : null;
        return $data;
    }

    private function validateReservationData(array $data): void
    {
        if (empty($data['startDate']) || !gettype($data['startDate']) === 'DateTimeInterface') {
            throw new \InvalidArgumentException("Start date is required");
        }
        if (empty($data['endDate']) || !gettype($data['endDate']) === 'DateTimeInterface') {
            throw new \InvalidArgumentException("End date is required");
        }
        if (empty($data['amountPeople']) || !is_int($data['amountPeople']) || $data['amountPeople'] <= 0) {
            throw new \InvalidArgumentException("invalid input for amount of people");
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("invalid input for email");
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
            $Reservation = new ReservationEntity();
            $Reservation->setRestaurant($data['restaurantId'] ?? null);
            $Reservation->setStartDate($data['startDate']);
            $Reservation->setEndDate($data['endDate']);
            $Reservation->setAmountPeople($data['amountPeople']);
            $Reservation->setEmail($data['email']);

            $this->ReservationRepository->save($Reservation);

            return $Reservation;
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
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

            if (isset($data['restaurantId'])) {
                $Reservation->setRestaurantId($data['restaurantId']);
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
            // Handle the exception as needed, e.g., log it or rethrow
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
}