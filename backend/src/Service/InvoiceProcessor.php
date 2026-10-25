<?php
declare(strict_types=1);
namespace App\Service;
class InvoiceProcessor
{

    public function __construct(private InvoiceCalculator $invoiceCalculator,
    private GenerateCsv $csvGenerator,
    private SaveData $saveData,
    private EmailNotifier $emailClass
    ){
    }
    public function processInvoice(array $lines, string $emailAddress):array
    {
        $output = [];
        $invoiceVatTotal = $this->invoiceCalculator->calculateInvoiceVat($lines);
        $csv = $this->csvGenerator->generateCsv($lines);
        $this->saveData->saveData($lines);
        $this->emailClass->sendEmail($emailAddress, $invoiceVatTotal);
        array_push($output, $invoiceVatTotal, $csv);
        return $output;
    }
}

