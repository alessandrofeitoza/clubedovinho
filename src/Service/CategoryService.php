<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Exception\ResourceNotFoundException;
use App\Repository\Interface\CategoryRepositoryInterface;
use App\Service\Interface\CategoryServiceInterface;
use Symfony\Component\Uid\Uuid;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function find(string $id): Category
    {
        $category = $this->categoryRepository->find(new Uuid($id));

        if (null === $category) {
            throw new ResourceNotFoundException(Category::class);
        }

        return $category;
    }

    public function findAll(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function insert(Category $category): Category
    {
        $category = $this->categoryRepository->save($category);

        return $category;
    }

    public function update(Category $category): Category
    {
        $this->categoryRepository->save($category);

        return $category;
    }

    public function remove(string $id): void
    {
        $category = $this->find($id);

        $this->categoryRepository->remove($category);
    }
}