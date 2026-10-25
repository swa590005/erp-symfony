<?php
declare(strict_types=1);
namespace App\Controller;

use App\Service\InvoiceCalculator;
use App\Service\LineTotalCalculatorInterface;
use App\Service\RoundedLineTotalCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

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
        $grossTotal = $invoiceCalculator->calculateInvoiceVat($linesTotal);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'Article List',
            'lines' => $linesTotal,
            'grossTotal' => $grossTotal
        ]);
    }

    #[Route('/article/budget', name: 'app_article_budget')]
    public function articleBudget(
        #[Autowire(service: RoundedLineTotalCalculator::class)]
        LineTotalCalculatorInterface $roundedLineTotalCalculator, InvoiceCalculator $invoiceCalculator
    ): Response
    {
        $lines = [
            ['name' => 'Bolts',   'quantity' => 10, 'unitPrice' => 0.50],
            ['name' => 'Washers', 'quantity' => 20,   'unitPrice' => 0.10],
            ['name' => 'Nuts', 'quantity' => 15, 'unitPrice' => 0.90],
        ];
        $linesTotal = $roundedLineTotalCalculator->addLinesTotal($lines);
        $grossTotal = $invoiceCalculator->calculateInvoiceVat($linesTotal);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'Article Budget',
            'lines' => $linesTotal,
            'grossTotal' => $grossTotal
        ]);
    }
}
