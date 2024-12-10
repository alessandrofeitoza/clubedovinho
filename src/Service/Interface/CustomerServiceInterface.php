<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Customer;

interface CustomerServiceInterface
{
    public function findAll(): array;

    public function find(string $id): Customer;

    public function insert(Customer $customer): Customer;

    public function remove (string $id): void;

    public function update(Customer $customer): Customer;
}