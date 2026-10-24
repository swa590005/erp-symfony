<?php
declare(strict_types=1);
namespace App\Service;

abstract class Discount
{
    abstract public function apply(float $amount):float;
}
