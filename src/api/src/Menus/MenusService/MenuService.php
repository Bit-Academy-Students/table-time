<?php

namespace App\Menus\MenusService;

/**
 * Bestandsnaam: MenuService.php
 *
 * Beschrijving:
    * Dit bestand bevat de MenuService klasse die verantwoordelijk is voor het afhandelen van HTTP-verzoeken
    * met betrekking tot Menu-entiteiten in de applicatie. De service biedt methoden voor het ophalen,
    * aanmaken, bijwerken en verwijderen van Menu's via de MenuRepository.
 *
 * Auteur: Johan Tol
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.6
 * - Laatste wijziging: 10 December 2025
 * - Beheer: Git
 */

use App\Menus\MenusEntity\MenuEntity;
use App\Menus\MenusRepository\MenuRepository;

class MenuService 
{
    private MenuRepository $MenuRepository;

    public function __construct(MenuRepository $MenuRepository)
    {
        $this->MenuRepository = $MenuRepository;
    }

    private function sanitizeMenuData(array $data): array
    {
        $data['email'] = preg_match('/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/', $data['email']) ? $data['email'] : '';
        return [
            'naam' => htmlspecialchars($data['naam'] ?? ''),
            'email' => filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'telefoonnummer' => preg_replace('/[^0-9+]/', '', $data['telefoonnummer'] ?? ''),
        ];
    }

    private function validateMenuData(array $data): void
    {
        if (!isset($data['productId']) || gettype($data['productId']) !== 'array') {
            throw new \InvalidArgumentException('veld productId is invalide');
        }

        foreach ($data['productId'] as $p) {
            if (!is_int($p)) {
                throw new \InvalidArgumentException('Each productId must be an integer.');
            }
        }

        if (!isset($data['restaurantId']) || !is_int($data['restaurantId'])) {
            throw new \InvalidArgumentException('veld restaurantId is invalide');
        }
    }

    public function getAllMenus(): array
    {
        return $this->MenuRepository->findAll();
    }

    public function getMenuById(int $id): ?MenuEntity
    {
        return $this->MenuRepository->find($id);
    }

    public function createMenu(array $data): MenuEntity
    {
        try {
            $data = $this->sanitizeMenuData($data);
            $this->validateMenuData($data);

            $Menu = new MenuEntity();

            foreach ($data['productId'] as $product) {
                $Menu->setProductIds($product);
            }

            $Menu->setRestaurantId($data['restaurantId']);

            $this->MenuRepository->save($Menu);

            return $Menu;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    public function updateMenu(int $id, array $data): ?MenuEntity
    {
        try {
            $data = $this->sanitizeMenuData($data);
            $this->validateMenuData($data);

            $Menu = $this->MenuRepository->find($id);
            if (!$Menu) {
                return null;
            }

            if (isset($data['productId'])) {
                foreach ($data['ProductId'] as $product) {
                    $Menu->setProductIds($product);
                }
            }

            if (isset($data['restaurantId'])) {
                $Menu->setRestaurantId($data['restaurantId']);
            }

            $this->MenuRepository->save($Menu);

            return $Menu;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    public function deleteMenu(int $id): bool
    {
        $Menu = $this->MenuRepository->find($id);
        if (!$Menu) {
            return false;
        }

        $this->MenuRepository->remove($Menu);

        return true;
    }
}
