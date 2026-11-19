<?php
declare(strict_types=1);
namespace App\Service;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class NewEmailService implements EmailNotifier
{
    public function __construct(private MailerInterface $mailer){}
    public function sendEmail(string $to, float $invoiceTotal, string $invoiceNumber):void
    {
        $email = (new Email())
            ->from('no-reply@localhost')
            ->to($to)
            ->subject((sprintf('New Invoice: %s ', $invoiceNumber)))
            ->text('Gross Total: ' . number_format($invoiceTotal, 2, ',', '').'€');

        $this->mailer->send($email);
    }
}
