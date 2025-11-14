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
        $Reservation->setNaam($data['naam']);
        $Reservation->setEmail($data['email'] ?? null);
        $Reservation->setTelefoonnummer($data['telefoonnummer'] ?? null);

        $this->ReservationRepository->save($Reservation);

        return $Reservation;
    }
    
    public function updateReservation(int $id, array $data): ?ReservationEntity
    {
        $Reservation = $this->ReservationRepository->find($id);
        if (!$Reservation) {
            return null;
        }

        if (isset($data['naam'])) {
            $Reservation->setNaam($data['naam']);
        }
        if (isset($data['email'])) {
            $Reservation->setEmail($data['email']);
        }
        if (isset($data['telefoonnummer'])) {
            $Reservation->setTelefoonnummer($data['telefoonnummer']);
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