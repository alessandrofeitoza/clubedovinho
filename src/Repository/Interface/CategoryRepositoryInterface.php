<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Category;
use Symfony\Component\Uid\Uuid;

interface CategoryRepositoryInterface
{
    public function save(Category $category): Category;

    public function findAll(): array;

    public function find(Uuid $id): ?Category;

    public function remove(Category $category): void;
}
