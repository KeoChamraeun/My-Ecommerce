<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case SHIPPED = 'shipped';
    case RETURNED = 'returned';
    case CANCELED = 'canceled';

    public static function labels(): array
    {
        return [
            self::PENDING->value    => 'Pending',
            self::PROCESSING->value => 'Processing',
            self::COMPLETED->value  => 'Completed',
            self::SHIPPED->value    => 'Shipped',
            self::RETURNED->value   => 'Returned',
            self::CANCELED->value   => 'Canceled',
        ];
    }

    public static function getValues(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

    public function label(): string
    {
        return self::labels()[$this->value];
    }

    public function translatedLabel(): string
    {
        return __('status.' . $this->value);
    }
}
