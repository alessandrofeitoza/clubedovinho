<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Repository\Interface\CustomerRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class CustomerRepository extends AbstractRepository implements CustomerRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Customer::class);
    }

    public function save(Customer $customer): Customer
    {
        $this->getEntityManager()->persist($customer);
        $this->getEntityManager()->flush();

        return $customer;
    }

    public function find(Uuid $id): ?Customer
    {
        return $this->getRepository()->find($id);
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function remove(Customer $customer): void
    {
        $this->getEntityManager()->remove($customer);
        $this->getEntityManager()->flush();
    }
}
