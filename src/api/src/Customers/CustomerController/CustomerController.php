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

    public function Create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $customer = $this->customerService->createCustomer($data);

        return new JsonResponse(
            ['customer' => $customer, 'response' => 'created']
        );
    }
    
    public function Update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $customer = $this->customerService->getCustomerById($id);

        $this->customerService->updateCustomer($id, $data);

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