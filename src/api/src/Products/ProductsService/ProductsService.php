<?php

namespace App\Products\ProductsService;

use App\Products\ProductsEntity\ProductsEntity;
use App\Products\ProductsRepository\ProductsRepository;

class ProductsService 
{
    private ProductsRepository $ProductRepository;

    public function __construct(ProductsRepository $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }

    public function getAllProducts(): array
    {
        return $this->ProductRepository->findAll();
    }

    public function getProductById(int $id): ?ProductsEntity
    {
        return $this->ProductRepository->find($id);
    }

    public function createProduct(array $data): ProductsEntity
    {
        try {
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is required");
            }
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
        $Product = new ProductsEntity();
        $Product->setNaam($data['naam']);
        $Product->setPrijs($data['prijs'] ?? null);
        $Product->setSoort($data['soort'] ?? null);
        $Product->setIngredients($data['ingredients'] ?? null);

        $this->ProductRepository->save($Product);

        return $Product;
    }
    
    public function updateProduct(int $id, array $data): ?ProductsEntity
    {
        $Product = $this->ProductRepository->find($id);
        if (!$Product) {
            return null;
        }

        if (isset($data['naam'])) {
            $Product->setNaam($data['naam']);
        }
        if (isset($data['prijs'])) {
            $Product->setPrijs($data['prijs']);
        }
        if (isset($data['soort'])) {
            $Product->setSoort($data['soort']);
        }
        if (isset($data['ingredients'])) {
            $Product->setIngredients($data['ingredients']);
        }

        $this->ProductRepository->save($Product);

        return $Product;
    }

    public function deleteProduct(int $id): bool
    {
        $Product = $this->ProductRepository->find($id);
        if (!$Product) {
            return false;
        }

        $this->ProductRepository->remove($Product);

        return true;
    }
}