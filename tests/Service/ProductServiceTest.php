<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\CountryFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Product;
use App\Exception\ResourceNotFoundException;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\ProductRepository;
use App\Service\CategoryService;
use App\Service\CountryService;
use App\Service\Interface\CategoryServiceInterface;
use App\Service\Interface\CountryServiceInterface;
use App\Service\ProductService;
use App\Service\Interface\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class ProductServiceTest extends KernelTestCase
{
    private ProductServiceInterface $service;
    private CategoryServiceInterface $categoryService;
    private CountryServiceInterface $countryService;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $this->categoryService = new CategoryService(
            new CategoryRepository($entityManager)
        );

        $this->countryService = new CountryService(
            new CountryRepository($entityManager)
        );

        $this->service = new ProductService(
            new ProductRepository($entityManager),
            $this->categoryService,
            $this->countryService
        );
    }

    public function testFindAllProductsUsingServiceRetrieveAnArrayOfProductEntities(): void
    {
        $products = $this->service->findAll();

        $this->assertCount(4, $products);
        $this->assertInstanceOf(Product::class, $products[0]);
    }

    public function testFindOneProductRetrieveAnProductEntity(): void
    {
        $product = $this->service->find(
            ProductFixtures::ID_1
        );

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Quinta dos Bons Ventos', $product->getName());
    }

    public function testThrowsAnExceptionWhenFindAProductThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Product::class));

        $this->service->find($id);
    }

    public function testThrowsAnExceptionWhenRemoveAProductThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Product::class));

        $this->service->remove($id);
    }

    public function testRemoveAProduct(): void
    {
        $this->assertCount(4, $this->service->findAll());

        $this->service->remove(ProductFixtures::ID_4);

        $this->assertCount(3, $this->service->findAll());
    }

    public function testInsertANewProduct(): void
    {
        $product = new Product(
            Uuid::v4(),
            'Product Test'
        );
        $product->setWeight(190);
        $product->setPrice(9.89);

        $this->service->insert($product, CategoryFixtures::ID_1, CountryFixtures::ID_1);

        $productCreated = $this->service->find($product->getId()->toString());

        $this->assertEquals('Product Test', $productCreated->getName());
        $this->assertEquals(9.89, $productCreated->getPrice());
    }

    public function testUpdateAProduct(): void
    {
        $product = $this->service->find(ProductFixtures::ID_1);

        $product->setPrice(109.95);

        $this->service->update($product);

        $productUpdated = $this->service->find(
            $product->getId()->toString()
        );

        $this->assertEquals(109.95, $productUpdated->getPrice());
    }
}
