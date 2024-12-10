<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Product;

interface ProductServiceInterface
{
    public function findAll(): array;

    public function find(string $id): Product;

    public function insert(Product $product, string $categoryId, string $countryId): Product;

    public function remove (string $id): void;

    public function update(Product $product): Product;
}