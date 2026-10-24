<?php
declare(strict_types=1);
namespace App\Service;

interface DiscountInterface
{
     public function apply(float $amount):float;
}
