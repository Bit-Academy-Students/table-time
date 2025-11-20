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

    private function sanitizeProductData(array $data): array
    {
        $data['naam'] = preg_replace('/\s+/', ' ', $data['naam']);

        $data['prijs'] = preg_replace('/.+/', '.', $data['prijs']);

        return $data;
    }

    private function validateProductData(array $data): void
    {
        if (empty($data['naam']) || gettype($data['naam']) !== 'string') {
            throw new \InvalidArgumentException("Naam is invalid");
        }
        if (empty($data['prijs']) || gettype($data['prijs']) !== 'double') {
            throw new \InvalidArgumentException("Er moet een getal opgegeven worden");
        }
        if (empty($data['soort']) || gettype($data['soort']) !== 'string') {
            throw new \InvalidArgumentException("Er moet een soort worden meegegeven");
        }
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
            $this->sanitizeProductData($data);
            $this->validateProductData($data);

            $Product = new ProductsEntity();
            $Product->setNaam($data['naam']);
            $Product->setPrijs($data['prijs']);
            $Product->setSoort($data['soort']);
            $Product->setIngredients($data['ingredients'] ?? null);

            $this->ProductRepository->save($Product);

            return $Product;
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
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
