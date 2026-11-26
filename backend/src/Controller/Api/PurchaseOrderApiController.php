<?php
declare(strict_types=1);
namespace App\Controller\Api;


use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;
use App\Enum\PurchaseOrderStatus;
use App\Repository\InvoiceRepository;
use App\Repository\PurchaseOrderRepository;
use App\Service\InvoiceCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('api/purchase-orders', name: 'app_api_purchase_order_list', methods: ['GET'])]
    public function list(PurchaseOrderRepository $purchaseOrderRepository): JsonResponse
    {
        $data = array_map(
            fn (PurchaseOrder $order) => [
                'orderNumber' => $order->getOrderNumber(),
                'supplierName' => $order->getSupplier()->getSupplierName(),
                'supplierNumber' => $order->getSupplier()->getSupplierNumber(),
                'orderDate' => $order->getOrderDate()->format('Y-m-d'),
                'status' => $order->getStatus(),
            ],
            $purchaseOrderRepository->findAll()
        );
        return $this->json($data);
    }

    #[Route('api/purchase-orders/{orderNumber}/status', name: 'app_api_purchase_order_status', methods: ['POST'])]
    public function changeStatus(string $orderNumber,
        Request $request,PurchaseOrderRepository $purchaseOrderRepository,
        EntityManagerInterface $entityManager):JsonResponse
    {

        $purchaseOrder = $purchaseOrderRepository->findOneByOrderNumber($orderNumber);
        if ($purchaseOrder === null) {
            return $this->json(['error' => 'Purchase order not found'], 404);
        }
        $data = $request->toArray();
        $statusText = $data['status'] ?? null;
        if($statusText === null){
            return $this->json(['error' => 'Status text cannot be empty'], 400);
        }
        $status = PurchaseOrderStatus::tryFrom($statusText);
        if($status === null ){
                return $this->json(['error' => 'Purchase order status not valid'], 422);
        }

        try {
            $purchaseOrder->setStatus($status);
            $entityManager->flush();
            return $this->json([
                'orderNumber' => $purchaseOrder->getOrderNumber(),
                'status' => $purchaseOrder->getStatus(),
            ], 200);
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 409);
        }
    }
    #[Route('/api/purchase-orders/{orderNumber}/invoice', name: 'app_api_purchase_order_invoice_api', methods: ['POST'])]
    public function createInvoice(string $orderNumber,
        InvoiceCreator $invoiceCreator,
        PurchaseOrderRepository $purchaseOrderRepository): JsonResponse
    {
        $purchaseOrder = $purchaseOrderRepository->findOneByOrderNumber($orderNumber);
        if ($purchaseOrder === null) {
            return $this->json(['error' => 'Purchase order not found'], 404);
        }
        try {
            $invoice = $invoiceCreator->createFromOrder($purchaseOrder);
            $data = [
                'orderNumber' => $invoice->getPurchaseOrder()->getOrderNumber(),
                'invoiceNumber'=> $invoice->getinvoiceNumber(),
                'invoiceDate' => $invoice->getinvoiceDate()->format('Y-m-d'),
                'netTotal' =>(float) $invoice->getnetTotal(),
                'grossTotal'=>(float) $invoice->getgrossTotal(),
                'discountAmount' =>(float) $invoice->getdiscountAmount(),
                'vatAmount' =>(float) $invoice->getvatAmount(),
                'vatRate' =>(float) $invoice->getvatRate(),
                'currency'=> 'EUR'
            ];
            return $this->json($data, 201);

        } catch (\DomainException $e) {
           return $this->json(['error' => $e->getMessage()], 409);
        }
    }
}
