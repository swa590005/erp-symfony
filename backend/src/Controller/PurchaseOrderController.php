<?php
declare(strict_types=1);
namespace App\Controller;

use App\Enum\PurchaseOrderStatus;
use App\Repository\PurchaseOrderRepository;
use App\Service\InvoiceCreator;
use App\Service\PurchaseOrderCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    /**
     * Shows one purchase order with its lines, line totals and gross total.
     *
     * Totals are calculated by PurchaseOrderCalculator, not stored.
     *
     * @throws NotFoundHttpException if no purchase order has this order number
     */
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

    /**
     * Handles the "Approve", "ship", "cancel" button on the purchase order page.
     *
     * Changes the order status.
     * PurchaseOrder::setStatus() checks whether the transition is allowed.
     * Shows a success or error flash message and redirects back to the order page.
     */
    #[Route('/purchase-order/{orderNumber}/status/{status}', name: 'app_purchase_order_status', methods: ['POST'])]
    public function changeStatus(
        string $orderNumber,
        PurchaseOrderRepository $purchaseOrderRepository,
        PurchaseOrderStatus $status,
        EntityManagerInterface $entityManager,
    ): Response {
        $purchaseOrder = $purchaseOrderRepository->findOneByOrderNumber($orderNumber);
        if ($purchaseOrder === null) {
            throw $this->createNotFoundException('Purchase order not found');
        }
        try {
            $purchaseOrder->setStatus($status);
            $entityManager->flush();
            $this->addFlash('success', 'Status changed successfully');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('app_purchase_order_show', ['orderNumber' => $orderNumber]);
    }

    /**
     * Handles the "Create Invoice" button on the purchase order page.
     *
     * Delegates the work to InvoiceCreator. Shows a success or error flash
     * message and redirects back to the order page.
     *
     * @throws NotFoundHttpException if no purchase order has this order number
     */
    #[Route('/purchase-order/{orderNumber}/invoice', name: 'app_purchase_order_invoice', methods: ['POST'])]
    public function createInvoice(
        string $orderNumber,
        InvoiceCreator $invoiceCreator,
        PurchaseOrderRepository $purchaseOrderRepository): Response
    {
        $purchaseOrder = $purchaseOrderRepository->findOneByOrderNumber($orderNumber);
        if ($purchaseOrder === null) {
            throw $this->createNotFoundException('Purchase order not found');
        }
        try {
            $invoiceCreator->createFromOrder($purchaseOrder);
            $this->addFlash('success', 'Invoice Created successfully');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_purchase_order_show', ['orderNumber' => $orderNumber]);
    }
}
