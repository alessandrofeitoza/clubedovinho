<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\UserSecurity;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class UserRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private UserRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = new UserRepository($this->entityManager);
    }

    public function testFindAllUsers(): void
    {
        $this->insertUsersForTest();

        $users = $this->repository->findAll();

        $this->assertEquals(4, count($users));
    }

    public function testFindOneUser(): void
    {
        $user = $this->repository->find(
            new Uuid(UserFixtures::ID_1)
        );

        $this->assertEquals('Alessandro Feitoza', $user->getName());
        $this->assertEquals('alessandro@email.com', $user->getEmail());
    }

    public function testUpdateOneUser(): void
    {
        $user = $this->repository->find(
            new Uuid(UserFixtures::ID_3)
        );

        $this->assertNull($user->getUpdatedAt());

        $user->setName('Maria das Graças');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $result = $this->repository->find($user->getId());

        $this->assertEquals('Maria das Graças', $result->getName());
        $this->assertNotNull($result->getUpdatedAt());
    }

    public function testFindOneUserThatDontExist(): void
    {
        $user = $this->repository->find(
            Uuid::v4()
        );

        $this->assertNull($user);
    }

    private function insertUsersForTest(): void
    {
        $user = new User(Uuid::v4(),'new_user@email.com');
        $user->setName('User Test');
        $user->setPassword(UserSecurity::encryptPassword('123456'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
