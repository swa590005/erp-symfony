<?php
declare(strict_types=1);
namespace App\Service;

class BulkDiscount implements DiscountInterface
{
    public function apply(float $amount): float {
        if ($amount < 100) {
            return $amount;
        }
        return round( $amount * 0.80, 2);                    // 20% off
    }
}
