<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DataFixtures\ProductFixtures;
use App\DataFixtures\PurchaseFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Purchase;
use App\Enum\PurchaseStatusEnum;
use App\Exception\ResourceNotFoundException;
use App\Logger\AuditLogger;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Service\CategoryService;
use App\Service\CountryService;
use App\Service\CustomerService;
use App\Service\ProductService;
use App\Service\Interface\CustomerServiceInterface;
use App\Service\Interface\ProductServiceInterface;
use App\Service\PurchaseService;
use App\Service\Interface\PurchaseServiceInterface;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class PurchaseServiceTest extends KernelTestCase
{
    private PurchaseServiceInterface $service;
    private CustomerServiceInterface $customerService;
    private ProductServiceInterface $productService;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $auditLogger = new AuditLogger(
            new Logger('audit')
        );

        $this->customerService = new CustomerService(
            new CustomerRepository($entityManager),
            $auditLogger
        );

        $this->productService = new ProductService(
            new ProductRepository($entityManager),
            new CategoryService(new CategoryRepository($entityManager), $auditLogger),
            new CountryService(new CountryRepository($entityManager)),
            $auditLogger
        );

        $this->service = new PurchaseService(
            new PurchaseRepository($entityManager),
            $this->customerService,
            $this->productService,
            $auditLogger
        );
    }

    public function testFindAllPurchasesUsingServiceRetrieveAnArrayOfPurchaseEntities(): void
    {
        $purchases = $this->service->findAll();

        $this->assertCount(4, $purchases);
        $this->assertInstanceOf(Purchase::class, $purchases[0]);
    }

    public function testFindOnePurchaseRetrieveAnPurchaseEntity(): void
    {
        $purchase = $this->service->find(
            PurchaseFixtures::ID_1
        );

        $this->assertInstanceOf(Purchase::class, $purchase);
        $this->assertEquals(PurchaseStatusEnum::OPENED, $purchase->getStatus());
    }

    public function testThrowsAnExceptionWhenFindAPurchaseThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Purchase::class));

        $this->service->find($id);
    }

    public function testThrowsAnExceptionWhenRemoveAPurchaseThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Purchase::class));

        $this->service->remove($id);
    }

    public function testRemoveAPurchase(): void
    {
        $this->assertCount(4, $this->service->findAll());

        $this->service->remove(PurchaseFixtures::ID_4);

        $this->assertCount(3, $this->service->findAll());
    }

    public function testInsertANewPurchase(): void
    {
        $customer = $this->customerService->find(UserFixtures::ID_1);

        $purchase = new Purchase(
            Uuid::v4()
        );
        $purchase->setAddress($customer->getAddresses()[0]);
        $purchase->setDistance(100);

        $this->service->insert($purchase, $customer->getId()->toString(), [ProductFixtures::ID_1]);

        $purchaseCreated = $this->service->find($purchase->getId()->toString());

        $this->assertEquals(PurchaseStatusEnum::OPENED, $purchaseCreated->getStatus());
        $this->assertEquals(110.0, $purchaseCreated->getTotalPrice());
    }

    public function testUpdateAPurchase(): void
    {
        $purchase = $this->service->find(PurchaseFixtures::ID_1);

        $purchase->setStatus(PurchaseStatusEnum::IN_DELIVERY_PROCESS);

        $this->service->update($purchase);

        $purchaseUpdated = $this->service->find(
            $purchase->getId()->toString()
        );

        $this->assertEquals(PurchaseStatusEnum::IN_DELIVERY_PROCESS, $purchaseUpdated->getStatus());
    }
}
