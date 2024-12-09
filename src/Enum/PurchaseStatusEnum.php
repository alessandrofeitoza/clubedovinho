<?php

declare(strict_types=1);

namespace App\Enum;

enum PurchaseStatusEnum: string
{
    case CANCELLED = 'cancelled';
    case OPENED = 'opened';
    case PREPARING = 'preparing';
    case AWAITING_SHIPMENT = 'awaiting shipment';
    case IN_DELIVERY_PROCESS = 'in delivery process';
    case FINISHED = 'finished';
}
