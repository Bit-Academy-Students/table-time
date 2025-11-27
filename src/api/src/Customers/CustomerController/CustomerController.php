<?php

namespace App\Customers\CustomerController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Customers\CustomerService\CustomerService;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends AbstractController
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function FindAll(): Response
    {
        $customers = $this->customerService->getAllCustomers();
        $customers = array_map(function ($customer) {
            return [
                'id' => $customer->getId(),
                'naam' => $customer->getNaam(),
                'email' => $customer->getEmail(),
                'telefoonnummer' => $customer->getTelefoonnummer(),
            ];
        }, $customers);

        return new JsonResponse(
            ['customers' => $customers]
        );
    }

    public function FindById(int $id): Response
    {
        try {
            $customer = $this->customerService->getCustomerById($id);

            if ($customer) {
                $customer = [
                    'id' => $customer->getId(),
                    'naam' => $customer->getNaam(),
                    'email' => $customer->getEmail(),
                    'telefoonnummer' => $customer->getTelefoonnummer(),
                ];
            }

            return new JsonResponse(
                ['customer' => $customer]
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
            $customer = $this->customerService->createCustomer($data);

            return new JsonResponse(
                ['customer' => $customer, 'response' => 'created']
            );
        } catch (\InvalidArgumentException $e) {
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
        $customer = $this->customerService->getCustomerById($id);
        $this->customerService->updateCustomer($id, $data);

        return new JsonResponse(
            ['customer' => $customer, 'response' => 'updated']
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
        try {
        $customer = $this->customerService->getCustomerById($id);

        $this->customerService->deleteCustomer($id);

        return new JsonResponse(
            ['customer' => $customer, 'response' => 'deleted']
        );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
