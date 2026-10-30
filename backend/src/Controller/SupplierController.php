<?php
declare(strict_types=1);
namespace App\Controller;

use App\Repository\SupplierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SupplierController extends AbstractController
{
    #[Route('/supplier', name: 'app_supplier')]
    public function index(SupplierRepository $supplierRepository): Response
    {
        $suppliers = $supplierRepository->findAll();
        return $this->render('supplier/index.html.twig', [
            'controller_name' => 'SupplierController',
            'suppliers' => $suppliers
        ]);
    }
}
