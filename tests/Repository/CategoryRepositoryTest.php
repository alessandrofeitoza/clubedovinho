<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\CategoryFixtures;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CategoryRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private CategoryRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = new CategoryRepository($this->entityManager);
    }

    public function testFindAllCategories(): void
    {
        $this->insertCategoriesForTest();

        $categories = $this->repository->findAll();

        $this->assertEquals(5, count($categories));
    }

    public function testFindOneCategory(): void
    {
        $category = $this->repository->find(
            new Uuid(CategoryFixtures::ID_1)
        );

        $this->assertEquals('Vinho', $category->getName());
    }

    public function testUpdateOneCategory(): void
    {
        $category = $this->repository->find(
            new Uuid(CategoryFixtures::ID_4)
        );
        $category->setName('Suco');

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $result = $this->repository->find($category->getId());

        $this->assertEquals('Suco', $result->getName());
    }

    private function insertCategoriesForTest(): void
    {
        $category = new Category(
            Uuid::v4(),
            'Category Test'
        );

        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
}
