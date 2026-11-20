<?php
declare(strict_types=1);
namespace App\Service;

/**
 * A discount rule for invoice lines.
 *
 * apply() returns the amount AFTER the discount, rounded to cents
 * (not the discount itself). Example: 200 with 20% off returns 160.
 */
interface DiscountInterface
{
    public function apply(float $amount):float;
}
