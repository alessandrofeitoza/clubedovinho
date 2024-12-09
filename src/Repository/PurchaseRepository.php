<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Purchase;
use App\Repository\Interface\PurchaseRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class PurchaseRepository extends AbstractRepository implements PurchaseRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Purchase::class);
    }

    public function save(Purchase $purchase): Purchase
    {
        $this->getEntityManager()->persist($purchase);
        $this->getEntityManager()->flush();

        return $purchase;
    }

    public function find(Uuid $id): ?Purchase
    {
        return $this->getRepository()->find($id);
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function remove(Purchase $purchase): void
    {
        $this->getEntityManager()->remove($purchase);
        $this->getEntityManager()->flush();
    }
}
