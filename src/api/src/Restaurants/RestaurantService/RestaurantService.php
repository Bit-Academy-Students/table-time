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
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is required");
            }
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
        $Restaurant = new RestaurantEntity();
        $Restaurant->setNaam($data['naam']);
        $Restaurant->setEmail($data['email'] ?? null);
        $Restaurant->setWachtwoord($data['wachtwoord'] ?? null);
        $Restaurant->setLocatie($data['locatie'] ?? null);
        $Restaurant->setTelefoonnummer($data['telefoonnummer'] ?? null);

        $this->RestaurantRepository->save($Restaurant);

        return $Restaurant;
    }
    
    public function updateRestaurant(int $id, array $data): ?RestaurantEntity
    {
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

        $this->RestaurantRepository->save($Restaurant);

        return $Restaurant;
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
}