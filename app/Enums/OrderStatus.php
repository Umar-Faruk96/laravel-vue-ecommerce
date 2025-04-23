<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Unpaid = 'unpaid';
    case Pending = 'pending';
    case Canceled = 'canceled';
    case Paid = 'paid';
    case Shipped = 'shipped';
    case Completed = 'completed';

    public static function getStatuses(): array
    {
        return [
            self::Unpaid->value => 'unpaid',
            self::Pending->value => 'pending',
            self::Canceled->value => 'canceled',
            self::Paid->value => 'paid',
            self::Shipped->value => 'shipped',
            self::Completed->value => 'completed',
        ];
    }
}
