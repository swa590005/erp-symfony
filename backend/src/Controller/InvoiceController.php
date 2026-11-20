<?php
declare(strict_types=1);
namespace App\Controller;

use App\Repository\InvoiceRepository;
use App\Service\InvoiceCsvExporter;
use App\Service\InvoiceProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InvoiceController extends AbstractController
{
    /**
     * Demo page from step 4: runs the old InvoiceProcessor on hard-coded lines
     * and shows the gross total and the CSV. Will be removed in step 9.
     */
    #[Route('/invoice', name: 'app_invoice')]
    public function index(InvoiceProcessor $invoiceProcessor): Response
    {
        $lines = [
            ['name' => 'Bolts',  'quantity' => 10, 'unitPrice' => 0.50],
            ['quantity' => 20,'name' => 'Washers', 'unitPrice' => 0.10],
        ];
        $emailAddress = 'a@b.de';
        $processorOutput = $invoiceProcessor->processInvoice($lines, $emailAddress);
        return $this->render('invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
            'invoiceProcessed' => $processorOutput
        ]);
    }

    /**
     * Same demo as index(), but uses the service app.invoice_processor.preview,
     * which is wired with LogEmailNotifier, so no email is ever sent.
     */
    #[Route('/invoice/preview', name: 'app_invoice_preview')]
    public function preview(
        #[Autowire(service: 'app.invoice_processor.preview')]
        InvoiceProcessor $invoiceProcessor): Response
    {
        $lines = [
            ['name' => 'Bolts',  'quantity' => 10, 'unitPrice' => 0.50],
            ['quantity' => 20,'name' => 'Washers', 'unitPrice' => 0.10],
        ];
        $emailAddress = 'a@b.de';
        $processorOutput = $invoiceProcessor->processInvoice($lines, $emailAddress);
        return $this->render('invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
            'invoiceProcessed' => $processorOutput
        ]);
    }

    /**
     * Downloads all invoices as a CSV file for the accountant.
     *
     * Delegates building the CSV to InvoiceCsvExporter. The Content-Disposition
     * header makes the browser save the file as invoices.csv instead of showing it.
     */
    #[Route('/invoice/download', name: 'app_invoice_download')]
    public function downloadInvoiceCsv(InvoiceCsvExporter $invoiceCsvExporter, InvoiceRepository $invoiceRepository): Response
    {
        $invoices = $invoiceRepository->findAll();
        $invoiceCsv = $invoiceCsvExporter->export($invoices);
        return new Response($invoiceCsv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="invoices.csv"',
        ]);
    }
}
