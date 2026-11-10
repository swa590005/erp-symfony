<?php
declare(strict_types=1);
namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $invoiceNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $invoiceDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $netTotal = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $discountAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $vatRate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $vatAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $grossTotal = null;

    #[ORM\OneToOne(inversedBy: 'invoice', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?PurchaseOrder $purchaseOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): static
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeImmutable
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(\DateTimeImmutable $invoiceDate): static
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    public function getNetTotal(): ?string
    {
        return $this->netTotal;
    }

    public function setNetTotal(string $netTotal): static
    {
        $this->netTotal = $netTotal;

        return $this;
    }

    public function getDiscountAmount(): ?string
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(string $discountAmount): static
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    public function getVatRate(): ?string
    {
        return $this->vatRate;
    }

    public function setVatRate(string $vatRate): static
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    public function getVatAmount(): ?string
    {
        return $this->vatAmount;
    }

    public function setVatAmount(string $vatAmount): static
    {
        $this->vatAmount = $vatAmount;

        return $this;
    }

    public function getGrossTotal(): ?string
    {
        return $this->grossTotal;
    }

    public function setGrossTotal(string $grossTotal): static
    {
        $this->grossTotal = $grossTotal;

        return $this;
    }

    public function getPurchaseOrder(): ?PurchaseOrder
    {
        return $this->purchaseOrder;
    }

    public function setPurchaseOrder(PurchaseOrder $purchaseOrder): static
    {
        $this->purchaseOrder = $purchaseOrder;

        return $this;
    }
}
