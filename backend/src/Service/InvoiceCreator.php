<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\Invoice;
use App\Entity\PurchaseOrder;
use App\Enum\PurchaseOrderStatus;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceCreator
{
    public function __construct(private PurchaseOrderCalculator $calculator, private EntityManagerInterface $entityManager,
    private EmailNotifier $emailNotifier){}

    /**
     * Creates an invoice for a purchase order.
     *
     * Only shipped orders without an existing invoice can be invoiced.
     * The invoice is saved to the database, and the supplier gets an email.
     *
     * @throws \DomainException if the order is not shipped or already invoiced
     */
    public function createFromOrder(PurchaseOrder $order): Invoice
    {
        if($order->getStatus() !== PurchaseOrderStatus::Shipped) {
            throw new \DomainException(sprintf('Order %s is not shipped', $order->getOrderNumber()));
        }
        if($order->getInvoice() !== null) {
            throw new \DomainException(sprintf('Order %s is already invoiced', $order->getOrderNumber()));
        }

        $invoice = new Invoice();
        $orderTotals = $this->calculator->calculateOrderTotal($order);
        $invoiceTotals = $orderTotals['invoiceTotals'];
        $invoice->setVatRate('19.00')
        ->setInvoiceNumber(sprintf('INV-%s', $order->getOrderNumber()))
        ->setInvoiceDate(new \DateTimeImmutable)
        ->setPurchaseOrder($order)
        ->setGrossTotal((string)$invoiceTotals['grossTotal'])
        ->setNetTotal((string) $invoiceTotals['netTotal'])
        ->setVatAmount((string)$invoiceTotals['vatAmount'])
        ->setDiscountAmount((string)$invoiceTotals['discountAmount']);
        $this->entityManager->persist($invoice);
        $this->entityManager->flush();
        $this->emailNotifier->sendEmail($invoice->getPurchaseOrder()->getSupplier()->getEmailAddress(),((float)$invoice->getGrossTotal()), $invoice->getInvoiceNumber());
        return $invoice;
    }
}
