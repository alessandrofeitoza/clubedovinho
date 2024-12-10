<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Category;

interface CategoryServiceInterface
{
    public function findAll(): array;

    public function find(string $id): Category;

    public function insert(Category $category): Category;

    public function remove (string $id): void;

    public function update(Category $category): Category;
}