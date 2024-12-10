<?php

declare(strict_types=1);

namespace App\Enum;

enum OperationLogTypeEnum: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case REMOVE = 'remove';
}
