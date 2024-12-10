<?php

declare(strict_types=1);

namespace App\Logger\Interface;

use Psr\Log\LoggerInterface;

interface AuditLoggerInterface
{
    public function created(string $className, array $data): void;
    public function updated(string $className, array $data): void;
    public function removed(string $className, array $data): void;

    public function getLogger(): LoggerInterface;
}