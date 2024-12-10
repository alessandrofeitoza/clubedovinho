<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Customer;
use App\Exception\ResourceNotFoundException;
use App\Repository\Interface\CustomerRepositoryInterface;
use App\Service\Interface\CustomerServiceInterface;
use Symfony\Component\Uid\Uuid;

class CustomerService implements CustomerServiceInterface
{
    public function __construct(
        private CustomerRepositoryInterface $repository
    ) {
    }

    public function find(string $id): Customer
    {
        $customer = $this->repository->find(new Uuid($id));

        if (null === $customer) {
            throw new ResourceNotFoundException(Customer::class);
        }

        return $customer;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function insert(Customer $customer): Customer
    {
        $customer = $this->repository->save($customer);

        return $customer;
    }

    public function update(Customer $customer): Customer
    {
        $this->repository->save($customer);

        return $customer;
    }

    public function remove(string $id): void
    {
        $customer = $this->find($id);

        $this->repository->remove($customer);
    }
}