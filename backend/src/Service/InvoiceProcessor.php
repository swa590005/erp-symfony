<?php
declare(strict_types=1);
namespace App\Service;
class InvoiceProcessor
{

    public function __construct(private InvoiceCalculator $invoiceCalculator,
    private GenerateCsv $csvGenerator,
    private SaveData $saveData,
    private EmailNotifier $emailClass,
    private LineTotalCalculatorInterface $lineTotalCalculator
    ){
    }
    public function processInvoice(array $lines, string $emailAddress):array
    {
        $output = [];
        $linesTotal = $this->lineTotalCalculator->addLinesTotal($lines);
        $invoiceVatTotal = $this->invoiceCalculator->calculateInvoiceVat($linesTotal);
        $csv = $this->csvGenerator->generateCsv($lines);
        $this->saveData->saveData($lines);
        $this->emailClass->sendEmail($emailAddress, $invoiceVatTotal);
        array_push($output, $invoiceVatTotal, $csv);
        return $output;
    }
}

