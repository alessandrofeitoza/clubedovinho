<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Purchase;

interface PurchaseServiceInterface
{
    public function findAll(): array;

    public function find(string $id): Purchase;

    public function insert(Purchase $purchase, string $customerId, array $products): Purchase;

    public function remove (string $id): void;

    public function update(Purchase $purchase): Purchase;
}