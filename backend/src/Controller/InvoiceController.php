<?php
declare(strict_types=1);
namespace App\Controller;

use App\Service\InvoiceProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InvoiceController extends AbstractController
{
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
}
