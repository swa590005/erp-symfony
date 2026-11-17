<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\Invoice;
use App\Entity\PurchaseOrder;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceCreator
{
    public function __construct(private PurchaseOrderCalculator $calculator, private EntityManagerInterface $entityManager){}
    public function createFromOrder(PurchaseOrder $order): Invoice
    {
        if(!$order->canBeInvoiced()){
            throw new \DomainException(sprintf('Order %s cannot be invoiced', $order->getOrderNumber()));
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
        return $invoice;
    }
}
