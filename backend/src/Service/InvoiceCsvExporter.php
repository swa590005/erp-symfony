<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\Invoice;


class InvoiceCsvExporter
{
    /**
     * @param Invoice[] $invoices
     */
    public function export(array $invoices): string
    {
        $csv = implode(';', ['invoice number', 'date', 'supplier number', 'supplier name',
            'net total', 'discount', 'VAT rate', 'VAT amount','gross total'])."\n";
        foreach ($invoices as $invoice) {
            $csv .= implode(';', [
                $invoice->getInvoiceNumber(),
                $invoice->getInvoiceDate()->format('Y-m-d'),
                $invoice->getPurchaseOrder()->getSupplier()->getSupplierNumber(),
                $invoice->getPurchaseOrder()->getSupplier()->getSupplierName(),
                $invoice->getNetTotal(), $invoice->getDiscountAmount(), $invoice->getVATRate(), $invoice->getVATAmount(),
               $invoice->getGrossTotal()
            ])."\n";
        }
        return $csv;
    }

}
