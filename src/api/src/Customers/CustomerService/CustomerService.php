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
        if (isset($data['wachtwoord'])) {
            $data['wachtwoord'] = password_hash($data['wachtwoord'], PASSWORD_DEFAULT);
        }
        if (isset($data['naam'])) {
            $data['naam'] = preg_replace('/\s+/', ' ', trim($data['naam']));
        }
        if (isset($data['email'])) {
            $data['email'] = preg_replace('/\s+/', ' ', trim($data['email']));
        }
        if (isset($data['telefoonnummer'])) {
            $data['telefoonnummer'] = preg_replace('/\s+/', ' ', trim($data['telefoonnummer']));
        }
        return $data;
    }

    private function validateCustomerData(array $data): void
    {
        try {
            if (empty($data['naam'])) {
                throw new \InvalidArgumentException("Naam is verplicht");
            }
            if (empty($data['wachtwoord'])) {
                throw new \InvalidArgumentException("Wachtwoord is verplicht");
            }
            if (isset($data['email']) && !preg_match('/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/', $data['email'])) {
                throw new \InvalidArgumentException("Ongeldig e-mailadres");
            }
            if (isset($data['telefoonnummer']) && !preg_match('/^\+?[0-9\s\-]+$/', $data['telefoonnummer'])) {
                throw new \InvalidArgumentException("Ongeldig telefoonnummer");
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
            $data = $this->sanitizeCustomerData($data);
            $this->validateCustomerData($data);
            $customer = new CustomerEntity();
            $customer->setNaam($data['naam']);
            $customer->setEmail($data['email'] ?? null);
            $customer->setTelefoonnummer($data['telefoonnummer'] ?? null);
            $customer->setWachtwoord($data['wachtwoord']);

            $this->customerRepository->save($customer);

            return $customer;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }
    
    public function updateCustomer(int $id, array $data): ?CustomerEntity
    {
        try { 
            $data = $this->sanitizeCustomerData($data);
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
        if (isset($data['wachtwoord'])) {
            $customer->setWachtwoord($data['wachtwoord']);
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

    public function Login(string $gebruikersnaam, string $wachtwoord): ?CustomerEntity
    {
        try {
            if (empty($gebruikersnaam) || empty($wachtwoord)) {
                throw new \InvalidArgumentException("Gebruikersnaam en wachtwoord zijn verplicht");
            }
            if(preg_match('/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/', $gebruikersnaam)) {
                try {
                    $customer = $this->customerRepository->findOneBy(['email' => $gebruikersnaam]);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException("kan email niet vinden");
                }
            } else {
                try {
                    $customer = $this->customerRepository->findOneBy(['naam' => $gebruikersnaam]);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException("kan naam niet vinden");
                }
            }
            if ($customer && password_verify($wachtwoord, $customer->getWachtwoord())) {
                return $customer;
            }
            return null;
        } catch (\InvalidArgumentException $e) {
            throw $e;

        }
    }
}