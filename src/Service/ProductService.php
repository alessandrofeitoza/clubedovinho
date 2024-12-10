<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Exception\ResourceNotFoundException;
use App\Logger\Interface\AuditLoggerInterface;
use App\Repository\Interface\ProductRepositoryInterface;
use App\Service\Interface\CategoryServiceInterface;
use App\Service\Interface\CountryServiceInterface;
use App\Service\Interface\ProductServiceInterface;
use Symfony\Component\Uid\Uuid;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private CategoryServiceInterface $categoryService,
        private CountryServiceInterface $countryService,
        private AuditLoggerInterface $auditLogger
    ) {
    }

    public function find(string $id): Product
    {
        $product = $this->repository->find(new Uuid($id));

        if (null === $product) {
            throw new ResourceNotFoundException(Product::class);
        }

        return $product;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function insert(Product $product, string $categoryId, string $countryId): Product
    {
        $product->setCategory($this->categoryService->find($categoryId));
        $product->setCountry($this->countryService->find($countryId));
        $product = $this->repository->save($product);

        $this->auditLogger->created(Product::class, [
            'id' => $product->getId(),
            'name' => $product->getName(),
        ]);

        return $product;
    }

    public function update(Product $product): Product
    {
        $this->repository->save($product);

        $this->auditLogger->updated(Product::class, [
            'id' => $product->getId(),
            'name' => $product->getName(),
        ]);

        return $product;
    }

    public function remove(string $id): void
    {
        $product = $this->find($id);

        $this->auditLogger->removed(Product::class, [
            'id' => $product->getId(),
            'name' => $product->getName(),
        ]);

        $this->repository->remove($product);
    }
}