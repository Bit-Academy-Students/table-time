<?php

namespace App\Restaurants\RestaurantController;

/**
 * Bestandsnaam: RestaurantsController.php
 *
 * Beschrijving:
    * Dit bestand bevat de RestaurantsController klasse die verantwoordelijk is voor het afhandelen van HTTP-verzoeken
    * met betrekking tot Restaurants-entiteiten in de applicatie. De controller biedt methoden voor het ophalen,
    * aanmaken, bijwerken en verwijderen van Restaurants via de RestaurantsService.
 *
 * Auteur: Keano Broekman
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.5
 * - Laatste wijziging: 12 December 2025
 * - Beheer: Git
 */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Restaurants\RestaurantService\RestaurantService;
use Symfony\Component\HttpFoundation\Request;

class RestaurantController extends AbstractController
{
    private RestaurantService $RestaurantService;

    public function __construct(RestaurantService $RestaurantService)
    {
        $this->RestaurantService = $RestaurantService;
    }

    public function FindAll(): Response
    {
        $Restaurants = $this->RestaurantService->getAllRestaurants();
        $Restaurants = array_map(function ($Restaurant) {
            return [
                'id' => $Restaurant->getId(),
                'naam' => $Restaurant->getNaam(),
                'locatie' => $Restaurant->getLocatie(),
                'telefoonnummer' => $Restaurant->getTelefoonnummer(),
                'maxcapacity' => $Restaurant->getMaxCapacity(),
                'email' => $Restaurant->getEmail(),
            ];
        }, $Restaurants);


        return new JsonResponse(
            ['Restaurants' => $Restaurants]
        );
    }

    public function FindById(int $id): Response
    {
        $Restaurant = $this->RestaurantService->getRestaurantById($id);
        $Restaurants = [
            'id' => $Restaurant->getId(),
            'naam' => $Restaurant->getNaam(),
            'locatie' => $Restaurant->getLocatie(),
            'telefoonnummer' => $Restaurant->getTelefoonnummer(),
            'maxcapacity' => $Restaurant->getMaxCapacity(),
            'email' => $Restaurant->getEmail(),
        ];

        return new JsonResponse(
            ['Restaurant' => $Restaurants]
        );
    }

    public function Create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Restaurant = $this->RestaurantService->createRestaurant($data);

        return new JsonResponse(
            ['Restaurant' => $Restaurant, 'response' => 'created']
        );
    }

    public function Update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Restaurant = $this->RestaurantService->getRestaurantById($id);

        $this->RestaurantService->updateRestaurant($id, $data);

        return new JsonResponse(
            ['Restaurant' => $Restaurant, 'response' => 'updated']
        );
    }

    public function Delete(int $id): Response
    {
        $Restaurant = $this->RestaurantService->getRestaurantById($id);

        $this->RestaurantService->deleteRestaurant($id);

        return new JsonResponse(
            ['Restaurant' => $Restaurant, 'response' => 'deleted']
        );
    }

    public function Authenticate(Request $request): Response
    {
        // Haal data op uit request body
        $data = json_decode($request->getContent(), true);

        // Debug logging (verwijder later)
        error_log('Request content: ' . $request->getContent());
        error_log('Parsed data: ' . print_r($data, true));

        // Controleer of data niet null of leeg is
        if (!$data || !isset($data['email']) || !isset($data['wachtwoord'])) {
            return new JsonResponse(
                ['response' => 'missing_credentials', 'error' => 'Email en wachtwoord zijn verplicht'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $Restaurant = $this->RestaurantService->authenticateRestaurant($data);

            if ($Restaurant) {
                return new JsonResponse([
                    'Restaurant' => [
                        'id' => $Restaurant->getId(),
                        'naam' => $Restaurant->getNaam(),
                        'email' => $Restaurant->getEmail(),
                        'locatie' => $Restaurant->getLocatie(),
                        'telefoonnummer' => $Restaurant->getTelefoonnummer(),
                        'maxcapacity' => $Restaurant->getMaxCapacity(),
                    ],
                    'response' => 'authenticated'
                ], Response::HTTP_OK);
            } else {
                return new JsonResponse(
                    ['response' => 'authentication_failed', 'error' => 'Onjuiste inloggegevens'],
                    Response::HTTP_UNAUTHORIZED
                );
            }
        } catch (\Exception $e) {
            error_log('Authentication error: ' . $e->getMessage());
            return new JsonResponse(
                ['response' => 'error', 'error' => 'Er is een fout opgetreden bij het inloggen'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
