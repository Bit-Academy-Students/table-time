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
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is required");
            }
        } catch (\InvalidArgumentException $e) {
            // Handle the exception as needed, e.g., log it or rethrow
            throw $e;
        }
        $customer = new CustomerEntity();
        $customer->setNaam($data['naam']);
        $customer->setEmail($data['email'] ?? null);
        $customer->setTelefoonnummer($data['telefoonnummer'] ?? null);
        $customer->setWachtwoord($data['wachtwoord']);

        $this->customerRepository->save($customer);

        return $customer;
    }
    
    public function updateCustomer(int $id, array $data): ?CustomerEntity
    {
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
        if (isset($data['wachtwoord'])) {
            $customer->setWachtwoord($data['wachtwoord']);
        }

        $this->customerRepository->save($customer);

        return $customer;
    }

    public function deleteCustomer(int $id): bool
    {
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            return false;
        }

        $this->customerRepository->remove($customer);

        return true;
    }
}