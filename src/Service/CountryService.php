<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Country;
use App\Exception\ResourceNotFoundException;
use App\Repository\Interface\CountryRepositoryInterface;
use App\Service\Interface\CountryServiceInterface;
use Symfony\Component\Uid\Uuid;

class CountryService implements CountryServiceInterface
{
    public function __construct(
        private CountryRepositoryInterface $countryRepository
    ) {
    }

    public function find(string $id): Country
    {
        $country = $this->countryRepository->find(new Uuid($id));

        if (null === $country) {
            throw new ResourceNotFoundException(Country::class);
        }

        return $country;
    }

    public function findAll(): array
    {
        return $this->countryRepository->findAll();
    }
}