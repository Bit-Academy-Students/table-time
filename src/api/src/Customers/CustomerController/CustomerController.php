<?php 

namespace App\Customers\CustomerController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Customers\CustomerService\CustomerService;

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

        return new JsonResponse(
            ['customers' => $customers]
        );
    }
    
    public function FindById(int $id): Response
    {
        $customer = $this->customerService->getCustomerById($id);

        return new JsonResponse(
            ['customer' => $customer]
        );
    }

    public function Create(array $data): Response
    {
        $customer = $this->customerService->createCustomer(
            $data['naam'],
            $data['email'] ?? null,
            $data['telefoonnummer'] ?? null
        );

        return new JsonResponse(
            ['customer' => $customer, 'response' => 'created']
        );
    }
    
    public function Update(int $id, array $data): Response
    {
        $customer = $this->customerService->getCustomerById($id);

        $this->customerService->updateCustomer(
            $id,
            $data['naam'] ?? $customer->getNaam(),
            $data['email'] ?? $customer->getEmail(),
            $data['telefoonnummer'] ?? $customer->getTelefoonnummer()
        );

        return new JsonResponse(
            ['customer' => $customer, 'response' => 'updated']
        );
    }
    
    public function Delete(int $id): Response
    {
        $customer = $this->customerService->getCustomerById($id);

        $this->customerService->deleteCustomer($id);

        return new JsonResponse(
            ['customer' => $customer, 'response' => 'deleted']
        );
    }
}