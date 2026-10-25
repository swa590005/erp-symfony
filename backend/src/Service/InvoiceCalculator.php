<?php
declare(strict_types=1);
namespace App\Service;
class InvoiceCalculator
{
    public function __construct(private DiscountInterface $discount)
    {}
    public function calculateInvoiceVat(array $lines):float
    {
        return array_sum(array_map(fn($line)=>round(($this->discount->apply($line['lineTotal'])) * 1.19,2), $lines));
    }

}
