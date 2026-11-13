<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\Invoice;
use App\Entity\PurchaseOrder;

class InvoiceCreator
{
    public function createFromOrder(PurchaseOrder $order): Invoice
    {
        if(!$order->canBeInvoiced()){
            throw new \DomainException(sprintf('Order %s cannot be invoiced', $order->getOrderNumber()));
        }

        $invoice = new Invoice();
        $invoice->setVatRate('19.00')
        ->setInvoiceNumber(sprintf('INV-%s', $order->getOrderNumber()))
        ->setInvoiceDate(new \DateTimeImmutable)
        ->setPurchaseOrder($order);
        return $invoice;
    }
}
