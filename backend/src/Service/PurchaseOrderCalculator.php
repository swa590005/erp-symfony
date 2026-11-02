<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;

class PurchaseOrderCalculator
{
    public function __construct(private LineTotalCalculatorInterface $lineTotalCalculator, private InvoiceCalculator $invoiceCalculator){}

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
        $linesTotal =$this->lineTotalCalculator->addLinesTotal($lines);
        $grossTotal= $this->invoiceCalculator->calculateInvoiceVat($linesTotal);
        return [
            'linesTotal' => $linesTotal,
            'grossTotal' => $grossTotal
        ];
    }
}
