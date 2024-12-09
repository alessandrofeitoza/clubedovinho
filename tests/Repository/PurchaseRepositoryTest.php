<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\ProductFixtures;
use App\DataFixtures\PurchaseFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Enum\PurchaseStatusEnum;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class PurchaseRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private PurchaseRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = new PurchaseRepository($this->entityManager);
    }

    public function testFindAllPurchases(): void
    {
        $this->insertPurchasesForTest();

        $purchases = $this->repository->findAll();

        $this->assertEquals(5, count($purchases));
    }

    public function testFindOnePurchase(): void
    {
        $purchase = $this->repository->find(
            new Uuid(PurchaseFixtures::ID_2)
        );

        $this->assertEquals('Alessandro Feitoza', $purchase->getCustomer()->getUser()->getName());
        $this->assertEquals('Chile', $purchase->getProducts()->first()->getCountry()->getName());
        $this->assertEquals(PurchaseStatusEnum::OPENED->value, $purchase->getStatus()->value);
    }

    public function testUpdateOnePurchase(): void
    {
        $purchase = $this->repository->find(
            new Uuid(PurchaseFixtures::ID_4)
        );
        $purchase->setStatus(PurchaseStatusEnum::IN_DELIVERY_PROCESS);

        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        $result = $this->repository->find($purchase->getId());

        $this->assertNotNull($result->getUpdatedAt());
        $this->assertEquals(PurchaseStatusEnum::IN_DELIVERY_PROCESS, $result->getStatus());
    }

    public function testFindOnePurchaseThatDontExist(): void
    {
        $purchase = $this->repository->find(
            Uuid::v4()
        );

        $this->assertNull($purchase);
    }

    private function insertPurchasesForTest(): void
    {
        $product1 = $this->entityManager
            ->getRepository(Product::class)
            ->find(ProductFixtures::ID_1);
        $product2 = $this->entityManager
            ->getRepository(Product::class)
            ->find(ProductFixtures::ID_1);
        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->find(UserFixtures::ID_1);

        $purchase = new Purchase(Uuid::v4(),'Purchase Test');
        $purchase->setCustomer($customer);
        $purchase->addProduct($product1);
        $purchase->addProduct($product2);
        $purchase->setDistance(100);
        $purchase->setDeliveryCost(20);
        $purchase->setTotalPrice(120);
        $purchase->setAddress(
            $customer->getAddresses()[0]
        );

        $this->entityManager->persist($purchase);
        $this->entityManager->flush();
    }
}
