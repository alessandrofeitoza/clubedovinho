<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DataFixtures\UserFixtures;
use App\Entity\Customer;
use App\Entity\User;
use App\Exception\ResourceNotFoundException;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use App\Service\CustomerService;
use App\Service\Interface\CustomerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CustomerServiceTest extends KernelTestCase
{
    private CustomerServiceInterface $service;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->service = new CustomerService(
            new CustomerRepository($entityManager)
        );
        $this->userRepository = new UserRepository($entityManager);
    }

    public function testFindAllCustomersUsingServiceRetrieveAnArrayOfCustomerEntities(): void
    {
        $customers = $this->service->findAll();

        $this->assertCount(3, $customers);
        $this->assertInstanceOf(Customer::class, $customers[0]);
    }

    public function testFindOneCustomerRetrieveAnCustomerEntity(): void
    {
        $customer = $this->service->find(
            UserFixtures::ID_1
        );

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Alessandro Feitoza', $customer->getUser()->getName());
    }

    public function testThrowsAnExceptionWhenFindACustomerThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Customer::class));

        $this->service->find($id);
    }

    public function testThrowsAnExceptionWhenRemoveACustomerThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Customer::class));

        $this->service->remove($id);
    }

    public function testRemoveACustomer(): void
    {
        $this->assertCount(3, $this->service->findAll());

        $this->service->remove(UserFixtures::ID_2);

        $this->assertCount(2, $this->service->findAll());
    }

    public function testInsertANewCustomer(): void
    {
        $id = Uuid::v4();
        $user = new User($id, 'user_test@email.com');
        $user->setPassword('1q2w3e');
        $user->setName('User test');

        $this->userRepository->save($user);

        $customer = new Customer($user);
        $customer->setPhone('85912341234');

        $this->service->insert($customer);

        $customerCreated = $this->service->find($id->toString());

        $this->assertEquals('User test', $customerCreated->getUser()->getName());
        $this->assertEquals('85912341234', $customerCreated->getPhone());
    }

    public function testUpdateACustomer(): void
    {
        $customer = $this->service->find(UserFixtures::ID_1);

        $customer->setPhone('85999990000');

        $this->service->update($customer);

        $customerUpdated = $this->service->find(
            $customer->getId()->toString()
        );

        $this->assertEquals('85999990000', $customerUpdated->getPhone());
    }
}
