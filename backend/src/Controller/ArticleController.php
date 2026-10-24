<?php

namespace App\Controller;

use App\Service\InvoiceCalculator;
use App\Service\LineTotalCalculatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(LineTotalCalculatorInterface $lineTotalCalculator, InvoiceCalculator $invoiceCalculator): Response
    {
        $lines = [
            ['name' => 'Bolts',   'quantity' => 10, 'unitPrice' => 0.50],
            ['name' => 'Washers', 'quantity' => 20,   'unitPrice' => 0.10],
            ['name' => 'Nuts', 'quantity' => 15, 'unitPrice' => 0.90],
        ];
        $linesTotal = $lineTotalCalculator->addLinesTotal($lines);
        $grossTotal = $invoiceCalculator->calculateInvoiceVat($lines);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'Article List',
            'lines' => $linesTotal,
            'grossTotal' => $grossTotal
        ]);
    }
}
