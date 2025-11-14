<?php

namespace App\Products\ProductService;

use App\Products\ProductEntity\ProductEntity;
use App\Products\ProductRepository\ProductRepository;

class ProductService 
{
    private ProductRepository $ProductRepository;

    public function __construct(ProductRepository $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }

    public function getAllProducts(): array
    {
        return $this->ProductRepository->findAll();
    }

    public function getProductById(int $id): ?ProductEntity
    {
        return $this->ProductRepository->find($id);
    }

    public function createProduct(array $data): ProductEntity
    {
        try {
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is required");
            }
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
        $Product = new ProductEntity();
        $Product->setNaam($data['naam']);
        $Product->setPrijs($data['prijs'] ?? null);
        $Product->setSoort($data['soort'] ?? null);
        $Product->setIngredients($data['ingredients'] ?? null);

        $this->ProductRepository->save($Product);

        return $Product;
    }
    
    public function updateProduct(int $id, array $data): ?ProductEntity
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