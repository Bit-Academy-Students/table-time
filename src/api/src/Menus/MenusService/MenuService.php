<?php

namespace App\Menus\MenusService;

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
        if (isset($data['ProductId']) && !isset($data['productId'])) {
            $data['productId'] = $data['ProductId'];
        }

        if (isset($data['productId']) && !is_array($data['productId'])) {
            $data['productId'] = [$data['productId']];
        }

        if (isset($data['productId'])) {
            $data['productId'] = array_map('intval', $data['productId']);
        }

        if (isset($data['restaurantId'])) {
            $data['restaurantId'] = (int)$data['restaurantId'];
        }

        return $data;
    }

    private function validateMenuData(array $data): void
    {
        if (!isset($data['productId']) || !is_array($data['productId']) || count($data['productId']) === 0) {
            throw new \InvalidArgumentException('productId must be a non-empty array of product IDs.');
        }

        foreach ($data['productId'] as $p) {
            if (!is_int($p)) {
                throw new \InvalidArgumentException('Each productId must be an integer.');
            }
        }

        if (!isset($data['restaurantId']) || !is_int($data['restaurantId'])) {
            throw new \InvalidArgumentException('restaurantId must be an integer.');
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
                foreach ($data['productId'] as $product) {
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
