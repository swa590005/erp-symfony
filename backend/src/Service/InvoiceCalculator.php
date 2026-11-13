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

    public function calculateInvoiceTotals(array $lines):array
    {
        $netTotal =round( array_sum(array_map(fn($line) => $line['lineTotal'], $lines)),2);
        $discountedTotal = array_sum(array_map(fn($line)=>$this->discount->apply($line['lineTotal']), $lines));
        $discountAmount = round(( $netTotal - $discountedTotal), 2);
        $grossTotal =round( $this->calculateInvoiceVat($lines),2);
        $vatAmount =round(($grossTotal - $discountedTotal),2);
        return ['netTotal' => $netTotal,
            'discountAmount' => $discountAmount,
            'vatAmount' => $vatAmount,
            'grossTotal'=> $grossTotal];

    }
}
