<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Product;
use Symfony\Component\Uid\Uuid;

interface ProductRepositoryInterface
{
    public function save(Product $product): Product;

    public function findAll(): array;

    public function find(Uuid $id): ?Product;

    public function remove(Product $product): void;
}
