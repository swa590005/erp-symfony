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
            'net total', 'discount', 'VAT rate', 'VAT amount','gross total','currency'])."\n";
        foreach ($invoices as $invoice) {
            $csv .= implode(';', [
                $invoice->getInvoiceNumber(),
                $invoice->getInvoiceDate()->format('Y-m-d'),
                $invoice->getPurchaseOrder()->getSupplier()->getSupplierNumber(),
                $invoice->getPurchaseOrder()->getSupplier()->getSupplierName(),
                    number_format((float) $invoice->getNetTotal(), 2, ',', ''),
                    number_format((float) $invoice->getDiscountAmount(), 2, ',', ''),
                    number_format((float) $invoice->getVATRate(), 2, ',', ''),
                    number_format((float) $invoice->getVATAmount(), 2, ',', ''),
               number_format((float) $invoice->getGrossTotal(), 2, ',', ''),'EUR'
            ])."\n";
        }
        return $csv;
    }

}
