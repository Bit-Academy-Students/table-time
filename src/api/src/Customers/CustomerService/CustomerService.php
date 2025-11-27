<?php

namespace App\Customers\CustomerService;

use App\Customers\CustomerEntity\CustomerEntity;
use App\Customers\CustomerRepository\CustomerRepository;

class CustomerService 
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    private function sanitizeCustomerData(array $data): array
    {
        return [];
    }

    private function validateCustomerData(array $data): void
    {
        try {
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is verplicht");
            }
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    public function getAllCustomers(): array
    {
        return $this->customerRepository->findAll();
    }

    public function getCustomerById(int $id): ?CustomerEntity
    {
        return $this->customerRepository->find($id);
    }

    public function createCustomer(array $data): CustomerEntity
    {
        try {
            $this->sanitizeCustomerData($data);
            $this->validateCustomerData($data);
            $customer = new CustomerEntity();
            $customer->setNaam($data['naam']);
            $customer->setEmail($data['email'] ?? null);
            $customer->setTelefoonnummer($data['telefoonnummer'] ?? null);

            $this->customerRepository->save($customer);

            return $customer;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }
    
    public function updateCustomer(int $id, array $data): ?CustomerEntity
    {
        try { 
            $this->sanitizeCustomerData($data);
            $this->validateCustomerData($data);
            $customer = $this->customerRepository->find($id);
            if (!$customer) {
                return null;
            }

            if (isset($data['naam'])) {
                $customer->setNaam($data['naam']);
            }
            if (isset($data['email'])) {
                $customer->setEmail($data['email']);
            }
            if (isset($data['telefoonnummer'])) {
                $customer->setTelefoonnummer($data['telefoonnummer']);
            }

            $this->customerRepository->save($customer);

            return $customer;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    public function deleteCustomer(int $id): bool
    {
        try {
            if (empty($id)) {
                throw new \InvalidArgumentException("ID is nodig voor verwijdering");
            }
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            return false;
        }

        $this->customerRepository->remove($customer);

        return true;
    }
}