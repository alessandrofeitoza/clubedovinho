<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function findAll(): array;

    public function find(Uuid $id): ?User;

    public function remove(User $user): void;
}
