<?php
declare(strict_types=1);
namespace App\Enum;

enum PurchaseOrderStatus:string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Shipped = 'shipped';
    case Cancelled= 'cancelled';
}
