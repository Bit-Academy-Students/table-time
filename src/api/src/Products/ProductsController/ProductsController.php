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

    public function __construct(ProductsService $ProductService)
    {
        $this->ProductService = $ProductService;
    }

    public function FindAll(): Response
    {
        $Products = $this->ProductService->getAllProducts();

        return new JsonResponse(
            ['Products' => $Products]
        );
    }
    
    public function FindById(int $id): Response
    {
        $Product = $this->ProductService->getProductById($id);

        return new JsonResponse(
            ['Product' => $Product]
        );
    }

    public function Create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Product = $this->ProductService->createProduct($data);

        return new JsonResponse(
            ['Product' => $Product, 'response' => 'created']
        );
    }
    
    public function Update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Product = $this->ProductService->getProductById($id);

        $this->ProductService->updateProduct($id, $data);

        return new JsonResponse(
            ['Product' => $Product, 'response' => 'updated']
        );
    }
    
    public function Delete(int $id): Response
    {
        $Product = $this->ProductService->getProductById($id);

        $this->ProductService->deleteProduct($id);

        return new JsonResponse(
            ['Product' => $Product, 'response' => 'deleted']
        );
    }
}