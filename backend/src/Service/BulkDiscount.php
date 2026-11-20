<?php
declare(strict_types=1);
namespace App\Service;

/**
 * 20% off for amounts of 100 € or more. Smaller amounts stay unchanged.
 */
class BulkDiscount implements DiscountInterface
{
    public function apply(float $amount): float {
        if ($amount < 100) {
            return $amount;
        }
        return round( $amount * 0.80, 2);                    // 20% off
    }
}
