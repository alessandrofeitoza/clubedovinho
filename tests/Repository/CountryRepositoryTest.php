<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\CountryFixtures;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CountryRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private CountryRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = new CountryRepository($this->entityManager);
    }

    public function testFindAllCategories(): void
    {
        $country = $this->repository->findAll();

        $this->assertEquals(6, count($country));
    }

    public function testFindOneCountry(): void
    {
        $country = $this->repository->find(
            new Uuid(CountryFixtures::ID_2)
        );

        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('Brasil', $country->getName());
    }

    public function testFindOneCountryThatDontExist(): void
    {
        $country = $this->repository->find(
            Uuid::v4()
        );

        $this->assertNull($country);
    }
}
