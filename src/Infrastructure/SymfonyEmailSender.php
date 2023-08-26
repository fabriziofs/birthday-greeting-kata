<?php

declare(strict_types=1);

namespace Infrastructure;

use Domain\Email;
use Domain\EmailSender;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\SendmailTransport;

final class SymfonyEmailSender implements EmailSender
{
    private Mailer $mailer;

    public function __construct()
    {
        $this->mailer = new Mailer(new SendmailTransport());
    }

    public function send(Email $email): void
    {
        $symfonyEmail = (new \Symfony\Component\Mime\Email())
            ->from($email->sender)
            ->to($email->recipient)
            ->subject($email->subject)
            ->text($email->body);

        $this->mailer->send($symfonyEmail);
    }
}
