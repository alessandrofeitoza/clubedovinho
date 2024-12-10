<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Country;

interface CountryServiceInterface
{
    public function findAll(): array;

    public function find(string $id): Country;
}