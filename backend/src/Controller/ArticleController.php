<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        $lines = [
            ['name' => 'Bolts',   'quantity' => 10, 'unitPrice' => 0.50],
            ['name' => 'Washers', 'quantity' => 20,   'unitPrice' => 0.10],
            ['name' => 'Nuts', 'quantity' => 15, 'unitPrice' => 0.90],
        ];
        // Take each line, keep its data, and add lineTotal (quantity × unitPrice) into a new array
        $linesTotal = array_map(fn(array $total) =>[...$total, 'lineTotal' => $total['quantity']*$total['unitPrice']] , $lines);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'Article List',
            'lines' => $linesTotal
        ]);
    }
}
