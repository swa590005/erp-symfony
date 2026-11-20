<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;

class PurchaseOrderCalculator
{
    public function __construct(private LineTotalCalculatorInterface $lineTotalCalculator, private InvoiceCalculator $invoiceCalculator){}

    /**
     * Adapter between Doctrine entities and the array-based calculators from step 4.
     *
     * Turns each PurchaseOrderLine into a plain array, then lets the calculators
     * add line totals and invoice totals. Uses the unit price saved on each order
     * line ($line->getUnitPrice()), not the article's current price.
     *
     * @return array{linesTotal: array, grossTotal: float, invoiceTotals: array}
     */
    public function calculateOrderTotal(PurchaseOrder $purchaseOrder):array
    {
        $orderLines = $purchaseOrder->getOrderLines()->toArray();
        $lines = array_map(
            fn(PurchaseOrderLine $line) => [
                'name' => $line->getArticle()->getName(),
                'articleNumber' => $line->getArticle()->getArticleNumber(),
                'quantity' => $line->getQuantity(),
                'unitPrice' =>(float) $line->getUnitPrice(),
            ],
            $orderLines
        );
        // lines array with extra col as linesTotal(price*unit)
        $linesTotal =$this->lineTotalCalculator->addLinesTotal($lines);
        $grossTotal= $this->invoiceCalculator->calculateGrossTotal($linesTotal);
        $invoiceTotals = $this->invoiceCalculator->calculateInvoiceTotals($linesTotal);
        return [
            'linesTotal' => $linesTotal,
            'grossTotal' => $grossTotal,
            'invoiceTotals' => $invoiceTotals
        ];
    }
}
