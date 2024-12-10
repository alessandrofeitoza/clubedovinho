<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DataFixtures\CategoryFixtures;
use App\Entity\Category;
use App\Exception\ResourceNotFoundException;
use App\Logger\AuditLogger;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Service\Interface\CategoryServiceInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CategoryServiceTest extends KernelTestCase
{
    private CategoryServiceInterface $service;
    private AuditLogger $auditLogger;
    private TestHandler $testLog;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $entityManager = $container->get('doctrine')->getManager();

        $this->auditLogger = new AuditLogger(
            new Logger('audit')
        );
        
        $this->testLog = new TestHandler();
        $this->auditLogger->getLogger()->pushHandler($this->testLog);

        $this->service = new CategoryService(
            new CategoryRepository($entityManager),
            $this->auditLogger
        );
    }

    public function testFindAllCategoriesUsingServiceRetrieveAnArrayOfCategoryEntities(): void
    {
        $categories = $this->service->findAll();

        $this->assertCount(4, $categories);
        $this->assertInstanceOf(Category::class, $categories[0]);
    }

    public function testFindOneCategoryRetrieveAnCategoryEntity(): void
    {
        $category = $this->service->find(
            CategoryFixtures::ID_1
        );

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Vinho', $category->getName());
    }

    public function testThrowsAnExceptionWhenFindACategoryThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Category::class));

        $this->service->find($id);
    }

    public function testThrowsAnExceptionWhenRemoveACategoryThatDoesNotExist(): void
    {
        $id = Uuid::v4()->toString();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Resource "%s" not found.', Category::class));

        $this->service->remove($id);
    }

    public function testRemoveACategory(): void
    {
        $this->assertCount(4, $this->service->findAll());

        $this->service->remove(CategoryFixtures::ID_3);

        $this->assertCount(3, $this->service->findAll());

        $this->assertTrue(
            $this->testLog->hasInfoThatContains('A ' . Category::class . ' entity has been deleted.')
        );
    }

    public function testInsertANewCategory(): void
    {
        $id = Uuid::v4();

        $category = new Category($id, 'Category test');
        $category->setDescription('description test');

        $this->service->insert($category);

        $categoryCreated = $this->service->find($id->toString());

        $this->assertEquals('Category test', $categoryCreated->getName());
        $this->assertEquals('description test', $categoryCreated->getDescription());

        $this->assertTrue(
            $this->testLog->hasInfoThatContains('A new ' . Category::class . ' has been created.')
        );
    }

    public function testUpdateACategory(): void
    {
        $category = $this->service->find(CategoryFixtures::ID_4);

        $this->assertNull($category->getDescription());

        $category->setDescription('description test 2');

        $this->service->update($category);

        $categoryUpdated = $this->service->find(
            $category->getId()->toString()
        );

        $this->assertEquals('description test 2', $categoryUpdated->getDescription());

        $this->assertTrue(
            $this->testLog->hasInfoThatContains('A ' . Category::class . ' entity has been updated.')
        );
    }
}
