<?php 

namespace App\Restaurants\RestaurantController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Restaurants\RestaurantService\RestaurantService;
use Symfony\Component\HttpFoundation\Request;

class RestaurantController extends AbstractController
{
    private RestaurantService $RestaurantService;

    public function __construct(RestaurantService $RestaurantService)
    {
        $this->RestaurantService = $RestaurantService;
    }

    public function FindAll(): Response
    {
        $Restaurants = $this->RestaurantService->getAllRestaurants();

        return new JsonResponse(
            ['Restaurants' => $Restaurants]
        );
    }
    
    public function FindById(int $id): Response
    {
        $Restaurant = $this->RestaurantService->getRestaurantById($id);

        return new JsonResponse(
            ['Restaurant' => $Restaurant]
        );
    }

    public function Create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Restaurant = $this->RestaurantService->createRestaurant($data);

        return new JsonResponse(
            ['Restaurant' => $Restaurant, 'response' => 'created']
        );
    }
    
    public function Update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Restaurant = $this->RestaurantService->getRestaurantById($id);

        $this->RestaurantService->updateRestaurant($id, $data);

        return new JsonResponse(
            ['Restaurant' => $Restaurant, 'response' => 'updated']
        );
    }
    
    public function Delete(int $id): Response
    {
        $Restaurant = $this->RestaurantService->getRestaurantById($id);

        $this->RestaurantService->deleteRestaurant($id);

        return new JsonResponse(
            ['Restaurant' => $Restaurant, 'response' => 'deleted']
        );
    }
}