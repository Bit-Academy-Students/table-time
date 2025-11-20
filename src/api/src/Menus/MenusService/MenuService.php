<?php

namespace App\Menus\MenuService;

use App\Menus\MenusEntity\MenuEntity;
use App\Menus\MenuRepository\MenuRepository;

class MenuService 
{
    private MenuRepository $MenuRepository;

    public function __construct(MenuRepository $MenuRepository)
    {
        $this->MenuRepository = $MenuRepository;
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
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is required");
            }
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
        $Menu = new MenuEntity();
        $Menu->setProductIds($data['productId']);
        $Menu->setRestaurantId($data['restaurantId']);
        $Menu->setStartDate($data['startDate']);
        $Menu->setEndDate($data['endDate']);
        $Menu->setAmountPeople($data['amountPeople']);

        $this->MenuRepository->save($Menu);

        return $Menu;
    }
    
    public function updateMenu(int $id, array $data): ?MenuEntity
    {
        $Menu = $this->MenuRepository->find($id);
        if (!$Menu) {
            return null;
        }

        if (isset($data['customerId'])) {
            $Menu->setCustomerId($data['customerId']);
        }
        if (isset($data['restaurantId'])) {
            $Menu->setRestaurantId($data['restaurantId']);
        }
        if (isset($data['startDate'])) {
            $Menu->setStartDate($data['startDate']);
        }
        if (isset($data['endDate'])) {
            $Menu->setEndDate($data['endDate']);
        }
        if (isset($data['amountPeople'])) {
            $Menu->setAmountPeople($data['amountPeople']);
        }

        $this->MenuRepository->save($Menu);

        return $Menu;
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