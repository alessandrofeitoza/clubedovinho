<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Customer;
use Symfony\Component\Uid\Uuid;

interface CustomerRepositoryInterface
{
    public function save(Customer $customer): Customer;

    public function findAll(): array;

    public function find(Uuid $id): ?Customer;

    public function remove(Customer $customer): void;
}
