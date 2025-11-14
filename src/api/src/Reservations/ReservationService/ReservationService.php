<?php

namespace App\Reservations\ReservationService;

use App\Reservations\ReservationEntity\ReservationEntity;
use App\Reservations\ReservationRepository\ReservationRepository;

class ReservationService 
{
    private ReservationRepository $ReservationRepository;

    public function __construct(ReservationRepository $ReservationRepository)
    {
        $this->ReservationRepository = $ReservationRepository;
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
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is required");
            }
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
        $Reservation = new ReservationEntity();
        $Reservation->setCustomerId($data['customerId']);
        $Reservation->setRestaurantId($data['restaurantId']);
        $Reservation->setStartDate($data['startDate']);
        $Reservation->setEndDate($data['endDate']);
        $Reservation->setAmountPeople($data['amountPeople']);

        $this->ReservationRepository->save($Reservation);

        return $Reservation;
    }
    
    public function updateReservation(int $id, array $data): ?ReservationEntity
    {
        $Reservation = $this->ReservationRepository->find($id);
        if (!$Reservation) {
            return null;
        }

        if (isset($data['customerId'])) {
            $Reservation->setCustomerId($data['customerId']);
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

        $this->ReservationRepository->save($Reservation);

        return $Reservation;
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