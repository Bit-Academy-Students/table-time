<?php

namespace App\Products\ProductsController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Products\ProductsService\ProductsService;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends AbstractController
{
    private ProductsService $ProductService;

    private function sanitizeProductData(array $data): array
    {
        $data['naam'] = preg_replace('/\s+/', ' ', $data['naam']);
        return [
            'naam' => htmlspecialchars($data['naam'] ?? ''),
            'prijs' => filter_var($data['prijs'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'beschrijving' => htmlspecialchars($data['beschrijving'] ?? ''),

        ];
    }

    private function validateProductId(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Invalide Product ID');
        }
    }

    public function __construct(ProductsService $ProductService)
    {
        $this->ProductService = $ProductService;
    }

    public function FindAll(): Response
    {
        $Products = $this->ProductService->getAllProducts();
        $Products = array_map(function ($Product) {
            return [
                'id' => $Product->getId(),
                'naam' => $Product->getNaam(),
                'prijs' => $Product->getPrijs(),
                'soort' => $Product->getSoort(),
                'ingredients' => $Product->getIngredients(),
                'menu' => $Product->getMenu(),
            ];
        }, $Products);

        return new JsonResponse(
            ['Products' => $Products]
        );
    }

    public function FindById(int $id): Response
    {
        try {
            $this->validateProductId($id);

            $Product = $this->ProductService->getProductById($id);

            return new JsonResponse(
                ['Product' => $Product]
            );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function Create(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->sanitizeProductData($data);
            $Product = $this->ProductService->createProduct($data);

            return new JsonResponse(
                ['Product' => $Product, 'response' => 'created']
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function Update(int $id, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $Product = $this->ProductService->getProductById($id);
            $this->sanitizeProductData($data);
            $this->validateProductId($id);

            $this->ProductService->updateProduct($id, $data);

            return new JsonResponse(
                ['Product' => $Product, 'response' => 'updated']
            );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function Delete(int $id): Response
    {
        try{
            $this->validateProductId($id);
        $Product = $this->ProductService->getProductById($id);

        $this->ProductService->deleteProduct($id);

        return new JsonResponse(
            ['Product' => $Product, 'response' => 'deleted']
        );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
