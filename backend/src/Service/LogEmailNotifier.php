<?php
declare(strict_types=1);
namespace App\Service;

/**
 * Placeholder notifier that sends nothing. Used where no email may ever go
 * out, for example /invoice/preview.
 */
class LogEmailNotifier implements EmailNotifier
{
    public function sendEmail(string $to, float $invoiceTotal, string $invoiceNumber):void{}
}
