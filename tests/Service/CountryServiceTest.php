<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DataFixtures\CountryFixtures;
use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Service\CountryService;
use App\Service\Interface\CountryServiceInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CountryServiceTest extends KernelTestCase
{
    private CountryServiceInterface $service;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->service = new CountryService(
            new CountryRepository($entityManager)
        );
    }

    public function testFindAllCountriesUsingServiceRetrieveAnArrayOfCountryEntities(): void
    {
        $countries = $this->service->findAll();

        $this->assertCount(6, $countries);
        $this->assertInstanceOf(Country::class, $countries[0]);
    }

    public function testFindOneCountryRetrieveAnCountryEntity(): void
    {
        $country = $this->service->find(
            CountryFixtures::ID_1
        );

        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('Argentina', $country->getName());
    }

    public function testThrowsAnExceptionWhenACountryDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Country::class));

        $this->service->find($id);
    }
}
