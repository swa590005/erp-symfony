<?php
declare(strict_types=1);
namespace App\Service;

class StandardDiscount implements DiscountInterface
{
    public function apply(float $amount):float
    {
        return round($amount * 0.95,2);
    }
}
