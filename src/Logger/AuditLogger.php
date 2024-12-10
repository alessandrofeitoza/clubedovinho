<?php

declare(strict_types=1);

namespace App\Logger;

use App\Enum\OperationLogTypeEnum;
use App\Logger\Interface\AuditLoggerInterface;
use Psr\Log\LoggerInterface;

class AuditLogger implements AuditLoggerInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function created(string $className, array $data): void
    {
        $this->logger->info('A new ' . $className . ' has been created.', [
            'data' => $data,
            'operation' => OperationLogTypeEnum::CREATE->value,
            'entity' => $className,
        ]);
    }

    public function updated(string $className, array $data): void
    {
        $this->logger->info('A ' . $className . ' entity has been updated.', [
            'data' => $data,
            'operation' => OperationLogTypeEnum::UPDATE->value,
            'entity' => $className,
        ]);
    }

    public function removed(string $className, array $data): void
    {
        $this->logger->info('A ' . $className . ' entity has been deleted.', [
            'data' => $data,
            'operation' => OperationLogTypeEnum::REMOVE->value,
            'entity' => $className,
        ]);
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
