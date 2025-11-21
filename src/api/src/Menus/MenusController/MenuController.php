<?php 

namespace App\Menus\MenusController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Menus\MenusService\MenuService;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends AbstractController
{
    private MenuService $MenuService;

    public function __construct(MenuService $MenuService)
    {
        $this->MenuService = $MenuService;
    }

    public function FindAll(): Response
    {
        $Menus = $this->MenuService->getAllMenus();
        $Menus = array_map(function ($Menu) {
            return [
                'id' => $Menu->getId(),
                'naam' => $Menu->getNaam(),
                'email' => $Menu->getEmail(),
                'telefoonnummer' => $Menu->getTelefoonnummer(),
            ];
        }, $Menus);

        return new JsonResponse(
            ['Menus' => $Menus]
        );
    }
    
    public function FindById(int $id): Response
    {
        $Menu = $this->MenuService->getMenuById($id);

        if ($Menu) {
            $Menu = [
                'id' => $Menu->getId(),
            ];
        }

        return new JsonResponse(
            ['Menu' => $Menu]
        );
    }

    public function Create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Menu = $this->MenuService->createMenu($data);

        return new JsonResponse(
            ['Menu' => $Menu, 'response' => 'created']
        );
    }
    
    public function Update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $Menu = $this->MenuService->getMenuById($id);

        $this->MenuService->updateMenu($id, $data);

        return new JsonResponse(
            ['Menu' => $Menu, 'response' => 'updated']
        );
    }
    
    public function Delete(int $id): Response
    {
        $Menu = $this->MenuService->getMenuById($id);

        $this->MenuService->deleteMenu($id);

        return new JsonResponse(
            ['Menu' => $Menu, 'response' => 'deleted']
        );
    }
}