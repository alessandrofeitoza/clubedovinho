<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Purchase;
use App\Exception\ResourceNotFoundException;
use App\Logger\Interface\AuditLoggerInterface;
use App\Repository\Interface\PurchaseRepositoryInterface;
use App\Service\Interface\CustomerServiceInterface;
use App\Service\Interface\ProductServiceInterface;
use App\Service\Interface\PurchaseServiceInterface;
use Symfony\Component\Uid\Uuid;

class PurchaseService implements PurchaseServiceInterface
{
    public function __construct(
        private PurchaseRepositoryInterface $repository,
        private CustomerServiceInterface $customerService,
        private ProductServiceInterface $productService,
        private AuditLoggerInterface $auditLogger,
    ) {
    }

    public function find(string $id): Purchase
    {
        $purchase = $this->repository->find(new Uuid($id));

        if (null === $purchase) {
            throw new ResourceNotFoundException(Purchase::class);
        }

        return $purchase;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function insert(Purchase $purchase, string $customerId, array $products): Purchase
    {
        $customer = $this->customerService->find($customerId);

        $purchase->setCustomer($customer);

        if (null === $purchase->getAddress()) {
            $purchase->setAddress($customer->getAddresses()[0]);
        }

        foreach ($products as $product) {
            $purchase->addProduct(
                $this->productService->find($product)
            );
        }

        $purchase->setDeliveryCost($this->calculateDeliveryCost($purchase));
        $purchase->setTotalPrice($this->calculateTotalPrice($purchase));

        $purchase = $this->repository->save($purchase);

        $this->auditLogger->created(Purchase::class, [
            'id' => $purchase->getId(),
            'customer' => $purchase->getCustomer()->getId(),
        ]);

        return $purchase;
    }

    public function update(Purchase $purchase): Purchase
    {
        $this->repository->save($purchase);

        $this->auditLogger->updated(Purchase::class, [
            'id' => $purchase->getId(),
            'customer' => $purchase->getCustomer()->getId(),
            'status' => $purchase->getStatus()->value,
        ]);

        return $purchase;
    }

    public function remove(string $id): void
    {
        $purchase = $this->find($id);

        $this->auditLogger->removed(Purchase::class, [
            'id' => $purchase->getId(),
            'customer' => $purchase->getCustomer()->getId(),
            'status' => $purchase->getStatus()->value,
        ]);

        $this->repository->remove($purchase);
    }

    private function calculateDeliveryCost(Purchase $purchase): float
    {
        return 10;
    }

    private function calculateTotalPrice(Purchase $purchase): float
    {
        return 100 + $purchase->getDeliveryCost();
    }
}