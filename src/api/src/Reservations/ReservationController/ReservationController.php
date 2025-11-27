<?php

namespace App\Reservations\ReservationController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Reservations\ReservationService\ReservationService;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{
    private ReservationService $ReservationService;

    private function validateId(int $id): int
    {
        $id > 0 ?: throw new \InvalidArgumentException('Invalid ID');
        return $id;
    }

    private function validatedata(array $data): array
    {
        isset($data['customer']) && isset($data['restaurant']) && isset($data['startDate']) && isset($data['endDate']) && isset($data['amountPeople'])
            ?: throw new \InvalidArgumentException('Missing required fields');
        return $data;
    }

    public function __construct(ReservationService $ReservationService)
    {
        $this->ReservationService = $ReservationService;
    }

    public function FindAll(): Response
    {
        $Reservations = $this->ReservationService->getAllReservations();
        $Reservations = array_map(function ($Reservation) {
            return [
                'id' => $Reservation->getId(),
                'customer' => $Reservation->getCustomer(),
                'restaurant' => $Reservation->getRestaurant(),
                'startDate' => $Reservation->getStartDate(),
                'endDate' => $Reservation->getEndDate(),
                'amountPeople' => $Reservation->getAmountPeople(),
            ];
        }, $Reservations);

        return new JsonResponse(
            ['Reservations' => $Reservations]
        );
    }

    public function FindById(int $id): Response
    {
        $Reservation = $this->ReservationService->getReservationById($id);

        if ($Reservation) {
            $Reservation = [
                'id' => $Reservation->getId(),
            ];
        }

        return new JsonResponse(
            ['Reservation' => $Reservation]
        );
    }

    public function Create(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->validatedata($data);
            $Reservation = $this->ReservationService->createReservation($data);

            return new JsonResponse(
                ['Reservation' => $Reservation, 'response' => 'created']
            );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function Update(int $id, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->validatedata($data);
            $this->validateId($id);
            $Reservation = $this->ReservationService->getReservationById($id);

            $this->ReservationService->updateReservation($id, $data);

            return new JsonResponse(
                ['Reservation' => $Reservation, 'response' => 'updated']
            );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function Delete(int $id): Response
    {
        try {
            $this->validateId($id);
            $Reservation = $this->ReservationService->getReservationById($id);

            $this->ReservationService->deleteReservation($id);

            return new JsonResponse(
                ['Reservation' => $Reservation, 'response' => 'deleted']
            );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
