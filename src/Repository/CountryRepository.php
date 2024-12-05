<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Country;
use App\Repository\Interface\CountryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Country::class);
    }

    public function find(Uuid $id): ?Country
    {
        return $this->getRepository()->find($id);
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }
}
