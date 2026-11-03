<?php
declare(strict_types=1);
namespace App\Enum;

enum PurchaseOrderStatus:string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Shipped = 'shipped';
    case Cancelled= 'cancelled';

    public function canTransitionTo(self $status): bool
    {
        return match ($this) {
            self::Pending => in_array($status, [self::Approved, self::Cancelled],true),
            self::Approved => in_array($status, [self::Shipped, self::Cancelled],true),
            self::Shipped => false,
            self::Cancelled => false,
        };
    }
}
