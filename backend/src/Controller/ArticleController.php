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
        return $this->render('article/index.html.twig', [
            'controller_name' => 'Article List',
            'lines' => [
                ['name' => 'Bolts',  'quantity' => 10, 'unitPrice' => 0.50],
                ['quantity' => 20,'name' => 'Washers', 'unitPrice' => 0.10],
                ['name' => 'Nuts', 'quantity' => 100, 'unitPrice' => 0.90]
            ],
        ]);
    }
}
