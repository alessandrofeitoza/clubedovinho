<?php

declare(strict_types=1);

namespace App\Exception;

use Doctrine\ORM\EntityNotFoundException;

class ResourceNotFoundException extends EntityNotFoundException
{
    public function __construct(string $entityClass)
    {
        parent::__construct(sprintf('Resource "%s" not found.', $entityClass));
    }
}