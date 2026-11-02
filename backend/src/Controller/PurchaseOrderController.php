<?php
declare(strict_types=1);
namespace App\Controller;

use App\Repository\PurchaseOrderRepository;
use App\Service\PurchaseOrderCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseOrderController extends AbstractController
{
    #[Route('/purchase/order', name: 'app_purchase_order')]
    public function index(): Response
    {
        return $this->render('purchase_order/index.html.twig', [
            'controller_name' => 'PurchaseOrderController',
        ]);
    }

    #[Route('/purchase-order/show/{orderNumber}', name: 'app_purchase_order_show')]
    public function show(
        string $orderNumber,
        PurchaseOrderRepository $purchaseOrderRepository,
        PurchaseOrderCalculator $purchaseOrderCalculator): Response
    {
        $purchaseOrder = $purchaseOrderRepository->findOneByOrderNumber($orderNumber);
        if ($purchaseOrder === null) {
            throw $this->createNotFoundException('Purchase order not found');
        }
        $orderLines = $purchaseOrderCalculator->calculateOrderTotal($purchaseOrder);
        return $this->render('purchase_order/show.html.twig', [
            'controller_name' => 'PurchaseOrderController',
            'purchaseOrder' => $purchaseOrder,
            'totals' => $orderLines
        ]);
    }
}
