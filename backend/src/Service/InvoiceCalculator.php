<?php
declare(strict_types=1);
namespace App\Service;

/**
 * Calculates invoice amounts from order lines that already have a 'lineTotal'.
 *
 * Rounding rule: first the discount is applied to each line and
 * then  19% VAT is added, and each line is rounded to cents.
 */
class InvoiceCalculator
{
    public function __construct(private DiscountInterface $discount)
    {}
    public function calculateGrossTotal(array $lines):float
    {
        return array_sum(array_map(fn($line)=>round(($this->discount->apply($line['lineTotal'])) * 1.19,2), $lines));
    }

    /**
     * @return array{netTotal: float, discountAmount: float, vatAmount: float, grossTotal: float}
     */
    public function calculateInvoiceTotals(array $lines):array
    {
        $netTotal =round( array_sum(array_map(fn($line) => $line['lineTotal'], $lines)),2);
        $discountedTotal = array_sum(array_map(fn($line)=>$this->discount->apply($line['lineTotal']), $lines));
        $discountAmount = round(( $netTotal - $discountedTotal), 2);
        $grossTotal =round( $this->calculateGrossTotal($lines),2);
        $vatAmount =round(($grossTotal - $discountedTotal),2);
        return ['netTotal' => $netTotal,
            'discountAmount' => $discountAmount,
            'vatAmount' => $vatAmount,
            'grossTotal'=> $grossTotal];

    }
}
