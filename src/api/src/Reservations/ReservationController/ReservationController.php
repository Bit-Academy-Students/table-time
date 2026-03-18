<?php 

namespace App\Reservations\ReservationController;

/**
 * Bestandsnaam: ReservationController.php
 *
 * Beschrijving:
    * Dit bestand bevat de ReservationController klasse die verantwoordelijk is voor het afhandelen van HTTP-verzoeken
    * met betrekking tot Reservation-entiteiten in de applicatie. De controller biedt methoden voor het ophalen,
    * aanmaken, bijwerken en verwijderen van Reservations via de ReservationService.
 *
 * Auteur: Johan Tol
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.7
 * - Laatste wijziging: 11 December 2025
 * - Beheer: Git
 */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Reservations\ReservationService\ReservationService;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{
    private ReservationService $ReservationService;

    public function __construct(ReservationService $ReservationService)
    {
        $this->ReservationService = $ReservationService;
    }

    // GET /api/Reservations - Alle reserveringen
    public function FindAll(): Response
    {
        $Reservations = $this->ReservationService->getAllReservations();
        $Reservations = array_map(function ($Reservation) {
            // NULL-CHECK voor restaurant!
            $restaurant = $Reservation->getRestaurant();
            
            return [
                'id' => $Reservation->getId(),
                'startDate' => $Reservation->getStartDate()->format('Y-m-d H:i:s'),
                'endDate' => $Reservation->getEndDate()->format('Y-m-d H:i:s'),
                'amountPeople' => $Reservation->getAmountPeople(),
                'email' => $Reservation->getEmail(),
                // Alleen restaurant info toevoegen als het bestaat
                'restaurant' => $restaurant ? [
                    'id' => $restaurant->getId(),
                    'naam' => $restaurant->getNaam(),
                    'locatie' => $restaurant->getLocatie(),
                    'telefoonnummer' => $restaurant->getTelefoonnummer(),
                    'maxcapacity' => $restaurant->getMaxCapacity(),
                    'email' => $restaurant->getEmail(),
                ] : null
            ];
        }, $Reservations);

        return new JsonResponse(
            ['Reservations' => $Reservations]
        );
    }
    
    // GET /api/Reservations/{id} - Specifieke reservering
    public function FindById(int $id): Response
    {
        $Reservation = $this->ReservationService->getReservationById($id);
        
        if (!$Reservation) {
            return new JsonResponse(
                ['error' => 'Reservering niet gevonden'],
                Response::HTTP_NOT_FOUND
            );
        }
        
        $restaurant = $Reservation->getRestaurant();
        
        $ReservationData = [
            'id' => $Reservation->getId(),
            'startDate' => $Reservation->getStartDate()->format('Y-m-d H:i:s'),
            'endDate' => $Reservation->getEndDate()->format('Y-m-d H:i:s'),
            'amountPeople' => $Reservation->getAmountPeople(),
            'email' => $Reservation->getEmail(),
            'restaurant' => $restaurant ? [
                'id' => $restaurant->getId(),
                'naam' => $restaurant->getNaam(),
                'locatie' => $restaurant->getLocatie(),
                'telefoonnummer' => $restaurant->getTelefoonnummer(),
                'maxcapacity' => $restaurant->getMaxCapacity(),
                'email' => $restaurant->getEmail(),
            ] : null
        ];

        return new JsonResponse(
            ['Reservation' => $ReservationData]
        );
    }

    // POST /api/Reservations - Nieuwe reservering (MET restaurantId!)
    public function Create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        // Validatie
        if (!isset($data['restaurantId'])) {
            return new JsonResponse(
                ['error' => 'restaurantId is verplicht'],
                Response::HTTP_BAD_REQUEST
            );
        }
        
        if (!isset($data['startDate']) || !isset($data['endDate']) || 
            !isset($data['amountPeople']) || !isset($data['email'])) {
            return new JsonResponse(
                ['error' => 'startDate, endDate, amountPeople en email zijn verplicht'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $Reservation = $this->ReservationService->createReservation($data);
            $restaurant = $Reservation->getRestaurant();

            return new JsonResponse(
                [
                    'Reservation' => [
                        'id' => $Reservation->getId(),
                        'startDate' => $Reservation->getStartDate()->format('Y-m-d H:i:s'),
                        'endDate' => $Reservation->getEndDate()->format('Y-m-d H:i:s'),
                        'amountPeople' => $Reservation->getAmountPeople(),
                        'email' => $Reservation->getEmail(),
                        'restaurant' => $restaurant ? [
                            'id' => $restaurant->getId(),
                            'naam' => $restaurant->getNaam(),
                        ] : null
                    ],
                    'response' => 'created'
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    // PUT /api/Reservations/{id} - Update reservering
    public function Update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $Reservation = $this->ReservationService->updateReservation($id, $data);
            
            if (!$Reservation) {
                return new JsonResponse(
                    ['error' => 'Reservering niet gevonden'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return new JsonResponse(
                [
                    'Reservation' => [
                        'id' => $Reservation->getId(),
                        'startDate' => $Reservation->getStartDate()->format('Y-m-d H:i:s'),
                        'endDate' => $Reservation->getEndDate()->format('Y-m-d H:i:s'),
                        'amountPeople' => $Reservation->getAmountPeople(),
                        'email' => $Reservation->getEmail(),
                    ],
                    'response' => 'updated'
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    // DELETE /api/Reservations/{id} - Verwijder reservering
    public function Delete(int $id): Response
    {
        try {
            $deleted = $this->ReservationService->deleteReservation($id);
            
            if (!$deleted) {
                return new JsonResponse(
                    ['error' => 'Reservering niet gevonden'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return new JsonResponse(
                ['response' => 'deleted']
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}