<?php
declare(strict_types=1);
namespace App\Service;

/**
 * Sends a notification when an invoice has been created.
 *
 * Implementations: NewEmailService (default, real email via Symfony Mailer)
 * and LogEmailNotifier (sends nothing, used by the invoice preview).
 */
interface EmailNotifier
{
    public function sendEmail(string $to, float $invoiceTotal, string $invoiceNumber):void;
}
