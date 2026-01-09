<!--
/**
 * Bestandsnaam: RestaurantsService.php
 *
 * Beschrijving:
    * Dit bestand bevat de RestaurantsService klasse die verantwoordelijk is voor het afhandelen van HTTP-verzoeken
    * met betrekking tot Restaurants-entiteiten in de applicatie. De service biedt methoden voor het ophalen,
    * aanmaken, bijwerken en verwijderen van Restaurants via de RestaurantsRepository.
 *
 * Auteur: Keano Broekman
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.5
 * - Laatste wijziging: 12 December 2025
 * - Beheer: Git
 */
-->

<?php

namespace App\Restaurants\RestaurantService;

use App\Restaurants\RestaurantEntity\RestaurantEntity;
use App\Restaurants\RestaurantRepository\RestaurantRepository;

class RestaurantService
{
    private RestaurantRepository $RestaurantRepository;

    public function __construct(RestaurantRepository $RestaurantRepository)
    {
        $this->RestaurantRepository = $RestaurantRepository;
    }

    private function sanitizeRestaurantData(array $data): array
    {
        if (isset($data['naam'])) {
            $data['naam'] = preg_replace('/\s+/', ' ', $data['naam']);
        }
        if (isset($data['wachtwoord'])) {
            $data['wachtwoord'] = password_hash($data['wachtwoord'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    private function validateRestaurantData(array $data): void
    {
        if (empty($data['naam'])) {
            throw new \InvalidArgumentException("Naam is required");
        }
        if (isset($data['maxCapacity']) && (!is_int($data['maxCapacity']) || $data['maxCapacity'] <= 0)) {
            throw new \InvalidArgumentException("Invalid input for max capacity");
        }
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }
        if (isset($data['telefoonnummer']) && !preg_match('/^\+?[0-9]{7,15}$/', $data['telefoonnummer'])) {
            throw new \InvalidArgumentException("Invalid phone number format");
        }
    }

    public function getAllRestaurants(): array
    {
        return $this->RestaurantRepository->findAll();
    }

    public function getRestaurantById(int $id): ?RestaurantEntity
    {
        return $this->RestaurantRepository->find($id);
    }

    public function createRestaurant(array $data): RestaurantEntity
    {
        try {
            $data = $this->sanitizeRestaurantData($data);
            $this->validateRestaurantData($data);
            $Restaurant = new RestaurantEntity();
            $Restaurant->setNaam($data['naam']);
            $Restaurant->setEmail($data['email'] ?? null);
            $Restaurant->setWachtwoord($data['wachtwoord'] ?? null);
            $Restaurant->setLocatie($data['locatie'] ?? null);
            $Restaurant->setTelefoonnummer($data['telefoonnummer'] ?? null);
            $Restaurant->setMaxCapacity($data['maxCapacity'] ?? 50);

            $this->RestaurantRepository->save($Restaurant);

            return $Restaurant;
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
    }

    public function updateRestaurant(int $id, array $data): ?RestaurantEntity
    {
        try {
            $data = $this->sanitizeRestaurantData($data);
            $this->validateRestaurantData($data);
            $Restaurant = $this->RestaurantRepository->find($id);
            if (!$Restaurant) {
                return null;
            }

            if (isset($data['naam'])) {
                $Restaurant->setNaam($data['naam']);
            }
            if (isset($data['email'])) {
                $Restaurant->setEmail($data['email']);
            }
            if (isset($data['wachtwoord'])) {
                $Restaurant->setWachtwoord($data['wachtwoord']);
            }
            if (isset($data['locatie'])) {
                $Restaurant->setLocatie($data['locatie']);
            }
            if (isset($data['telefoonnummer'])) {
                $Restaurant->setTelefoonnummer($data['telefoonnummer']);
            }
            if (isset($data['maxCapacity'])) {
                $Restaurant->setMaxCapacity($data['maxCapacity']);
            }

            $this->RestaurantRepository->save($Restaurant);

            return $Restaurant;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    public function deleteRestaurant(int $id): bool
    {
        $Restaurant = $this->RestaurantRepository->find($id);
        if (!$Restaurant) {
            return false;
        }

        $this->RestaurantRepository->remove($Restaurant);

        return true;
    }
    public function authenticateRestaurant(array $data): ?RestaurantEntity
    {
        if (empty($data['email']) || empty($data['wachtwoord'])) {
            throw new \InvalidArgumentException("Email and password are required for authentication");
        }

        $Restaurant = $this->RestaurantRepository->findOneBy(['email' => $data['email']]);
        if ($Restaurant && password_verify($data['wachtwoord'], $Restaurant->getWachtwoord())) {
            return $Restaurant;
        }

        return null;
    }
}