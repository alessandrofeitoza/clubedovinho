<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\CustomerRepository;
use App\ValueObject\Address;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CustomerRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private CustomerRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = new CustomerRepository($this->entityManager);
    }

    public function testFindAllCustomers(): void
    {
        $customers = $this->repository->findAll();

        $this->assertEquals(3, count($customers));
    }

    public function testFindOneCustomer(): void
    {
        $customer = $this->repository->find(
            new Uuid(UserFixtures::ID_1)
        );

        $this->assertEquals('Alessandro Feitoza', $customer->getUser()->getName());
        $this->assertEquals('85987651234', $customer->getPhone());
        $this->assertEquals('Av Augusto dos Anjos', $customer->getAddresses()[0]->getStreet());
    }

    public function testUpdateOneCustomer(): void
    {
        $customer = $this->repository->find(
            new Uuid(UserFixtures::ID_3)
        );

        $this->assertCount(0, $customer->getAddresses());

        $address = new Address('60352100');
        $address->setStreet('Street test');
        $address->setNumber('123');
        $address->setDistrict('Quintino Cunha');
        $address->setCity('Fortaleza');
        $address->setState('CE');

        $customer->setPhone('88988998899');
        $customer->addAddress($address);

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        $result = $this->repository->find($customer->getId());

        $this->assertEquals('88988998899', $result->getPhone());
        $this->assertCount(1, $result->getAddresses());
    }

    public function testFindOneCustomerThatDontExist(): void
    {
        $customer = $this->repository->find(
            Uuid::v4()
        );

        $this->assertNull($customer);
    }
}
