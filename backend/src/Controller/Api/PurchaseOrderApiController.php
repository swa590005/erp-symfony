<?php
declare(strict_types=1);
namespace App\Controller\Api;

use App\Entity\PurchaseOrderLine;
use App\Repository\PurchaseOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseOrderApiController extends AbstractController
{
    #[Route('/api/purchase-orders/{orderNumber}', name: 'app_api_purchase_order_show_api', methods: ['GET'])]
    public function show(string $orderNumber,
        PurchaseOrderRepository $purchaseOrderRepository): JsonResponse
    {
        $purchaseOrder = $purchaseOrderRepository->findOneByOrderNumber($orderNumber);
        if ($purchaseOrder === null) {
            return $this->json(['error' => 'Purchase order not found'], 404);
        }
        $data =[
            'orderNumber' => $purchaseOrder->getOrderNumber(),
            'supplierName' => $purchaseOrder->getSupplier()->getSupplierName(),
            'supplierNumber' => $purchaseOrder->getSupplier()->getSupplierNumber(),
            'orderDate' => $purchaseOrder->getOrderDate()->format('Y-m-d'),
            'status' => $purchaseOrder->getStatus(),
            'lines' => array_map(
                fn (PurchaseOrderLine $line) => [
                    'name' => $line->getArticle()->getName(),
                    'articleNumber' => $line->getArticle()->getArticleNumber(),
                    'quantity' => $line->getQuantity(),
                    'unitPrice' =>(float) $line->getUnitPrice(),
                ],
                $purchaseOrder->getOrderLines()->toArray()
            ),
        ];
        return $this->json($data);
    }
}
