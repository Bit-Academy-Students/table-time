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

    public function createCustomer(string $naam, ?string $email, ?string $telefoonnummer): CustomerEntity
    {
        $customer = new CustomerEntity();
        $customer->setNaam($naam);
        $customer->setEmail($email);
        $customer->setTelefoonnummer($telefoonnummer);

        $this->customerRepository->save($customer);

        return $customer;
    }
    
    public function updateCustomer(int $id, ?string $naam, ?string $email, ?string $telefoonnummer): ?CustomerEntity
    {
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            return null;
        }

        if ($naam !== null) {
            $customer->setNaam($naam);
        }
        if ($email !== null) {
            $customer->setEmail($email);
        }
        if ($telefoonnummer !== null) {
            $customer->setTelefoonnummer($telefoonnummer);
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