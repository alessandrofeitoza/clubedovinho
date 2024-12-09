<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Purchase;
use Symfony\Component\Uid\Uuid;

interface PurchaseRepositoryInterface
{
    public function save(Purchase $purchase): Purchase;

    public function findAll(): array;

    public function find(Uuid $id): ?Purchase;

    public function remove(Purchase $purchase): void;
}
