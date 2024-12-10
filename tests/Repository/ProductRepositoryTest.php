<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\CountryFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class ProductRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ProductRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = new ProductRepository($this->entityManager);
    }

    public function testFindAllProducts(): void
    {
        $this->insertProductsForTest();

        $products = $this->repository->findAll();

        $this->assertEquals(5, count($products));
    }

    public function testFindOneProduct(): void
    {
        $product = $this->repository->find(
            new Uuid(ProductFixtures::ID_2)
        );

        $this->assertEquals('Casillero del Diablo', $product->getName());
        $this->assertEquals('Chile', $product->getCountry()->getName());
        $this->assertEquals('Vinho', $product->getCategory()->getName());
    }

    public function testUpdateOneProduct(): void
    {
        $product = $this->repository->find(
            new Uuid(ProductFixtures::ID_4)
        );
        $product->setName('Vinho São Francisco');

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $result = $this->repository->find($product->getId());

        $this->assertEquals('Vinho São Francisco', $result->getName());
    }

    public function testFindOneProductThatDontExist(): void
    {
        $product = $this->repository->find(
            Uuid::v4()
        );

        $this->assertNull($product);
    }

    private function insertProductsForTest(): void
    {
        $country = $this->entityManager
            ->getRepository(Country::class)
            ->find(CountryFixtures::ID_1);
        $category = $this->entityManager
            ->getRepository(Category::class)
            ->find(CategoryFixtures::ID_1);

        $product = new Product(Uuid::v4(),'Product Test');
        $product->setCategory($category);
        $product->setCountry($country);
        $product->setWeight(500);
        $product->setPrice(10.90);
        $product->setAdditionalInfo('garrafa cristal');

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}
