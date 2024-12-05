<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Country;
use Symfony\Component\Uid\Uuid;

interface CountryRepositoryInterface
{
    public function findAll(): array;

    public function find(Uuid $id): ?Country;
}
