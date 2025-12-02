<?php

namespace App\Reservations\ReservationService;

use App\Reservations\ReservationEntity\ReservationEntity;
use App\Reservations\ReservationRepository\ReservationRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReservationService
{
    private ReservationRepository $ReservationRepository;
    private MailerInterface $mailer;

    public function __construct(
        ReservationRepository $ReservationRepository,
        MailerInterface $mailer
    ) {
        $this->ReservationRepository = $ReservationRepository;
        $this->mailer = $mailer;
    }

    private function sanitizeReservationData(array $data): array
    {
        return $data;
    }

    private function validateReservationData(array $data): void
    {
        if (empty($data['startDate'])) {
            throw new \InvalidArgumentException("Start date is required");
        }
        if (empty($data['endDate'])) {
            throw new \InvalidArgumentException("End date is required");
        }
        if (empty($data['amountPeople']) || !is_numeric($data['amountPeople']) || $data['amountPeople'] <= 0) {
            throw new \InvalidArgumentException("Invalid amount of people");
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
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
        $this->validateReservationData($data);

        $data['startDate'] = new \DateTimeImmutable($data['startDate']);
        $data['endDate'] = new \DateTimeImmutable($data['endDate']);

        $Reservation = new ReservationEntity();
        $Reservation->setEmail($data['email']);
        $Reservation->setRestaurant($data['restaurantId'] ?? null);
        $Reservation->setStartDate($data['startDate']);
        $Reservation->setEndDate($data['endDate']);
        $Reservation->setAmountPeople($data['amountPeople']);

        $this->ReservationRepository->save($Reservation);

        // 📧 Email versturen
        $email = (new Email())
            ->from('no-reply@jouwdomein.nl')
            ->to($Reservation->getEmail())
            ->subject('Bevestiging van uw reservering')
            ->html("
                <h2>Dank voor uw reservering!</h2>
                <p>Uw reservering is succesvol geplaatst.</p>
                <p><strong>Start:</strong> {$data['startDate']->format('Y-m-d H:i')}</p>
                <p><strong>Eind:</strong> {$data['endDate']->format('Y-m-d H:i')}</p>
                <p><strong>Personen:</strong> {$data['amountPeople']}</p>
            ");

        $this->mailer->send($email);

        return $Reservation;
    }

    public function updateReservation(int $id, array $data): ?ReservationEntity
    {
        $Reservation = $this->ReservationRepository->find($id);
        if (!$Reservation) {
            return null;
        }

        if (isset($data['email'])) {
            $Reservation->setEmail($data['email']);
        }
        if (isset($data['restaurantId'])) {
            $Reservation->setRestaurant($data['restaurantId']);
        }
        if (isset($data['startDate'])) {
            $Reservation->setStartDate(new \DateTimeImmutable($data['startDate']));
        }
        if (isset($data['endDate'])) {
            $Reservation->setEndDate(new \DateTimeImmutable($data['endDate']));
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
