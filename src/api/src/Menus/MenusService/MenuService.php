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

    private function validateMenuData(array $data): void
    {
        if (!isset($data['productId']) || gettype($data['productId']) !== 'array') {
            throw new \InvalidArgumentException('field productId is invalid');
        }
        if (!isset($data['restaurantId']) || !is_int($data['restaurantId'])) {
            throw new \InvalidArgumentException('field restaurantId is invalid');
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
            $this->validateMenuData($data);
            if (!isset($data['productId']) || !isset($data['restaurantId'])) {
                throw new \InvalidArgumentException('Missing required fields: productId and restaurantId');
            }
            $Menu = new MenuEntity();
            foreach ($data['ProductId'] as $product) {
                $Menu->setProductIds($product);
            }
            $Menu->setRestaurantId($data['restaurantId']);

            $this->MenuRepository->save($Menu);

            return $Menu;
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
    }
    
    public function updateMenu(int $id, array $data): ?MenuEntity
    {
        try {
            $this->validateMenuData($data);
            $Menu = $this->MenuRepository->find($id);
            if (!$Menu) {
                return null;
            }

            if (isset($data['ProductId'])) {
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
            // Handle the exception as needed, e.g., log it or rethrow
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