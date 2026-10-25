<?php
declare(strict_types=1);
namespace App\Service;
interface EmailNotifier
{
    public function sendEmail(string $to, float $invoiceTotal):void;
}
