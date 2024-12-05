<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractRepository
{
    private ObjectRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
        string $entityClass
    )
    {
        $this->repository = $entityManager->getRepository($entityClass);
    }

    protected function getEntityManager(): ObjectManager
    {
        return $this->entityManager;
    }

    public function getRepository(): ObjectRepository
    {
        return $this->repository;
    }
}
