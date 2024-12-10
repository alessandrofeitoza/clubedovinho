<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Exception\ResourceNotFoundException;
use App\Logger\AuditLogger;
use App\Repository\Interface\CategoryRepositoryInterface;
use App\Service\Interface\CategoryServiceInterface;
use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\Uid\Uuid;

#[WithMonologChannel('audit')]
class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private AuditLogger $auditLogger
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

        $this->auditLogger->created(Category::class, [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ]);

        return $category;
    }

    public function update(Category $category): Category
    {
        $this->categoryRepository->save($category);

        $this->auditLogger->updated(Category::class, [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ]);

        return $category;
    }

    public function remove(string $id): void
    {
        $category = $this->find($id);

        $this->auditLogger->removed(Category::class, [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ]);

        $this->categoryRepository->remove($category);
    }
}