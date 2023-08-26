<?php

use Domain\EmployeeRepository;
use Infrastructure\FileSystemEmployeeRepository;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mime\Email;

class BirthdayService
{
    private Mailer $mailer;
    private EmployeeRepository $repository;

    public function __construct(EmployeeRepository $repository = new FileSystemEmployeeRepository())
    {
        $this->repository = $repository;
    }

    public function sendGreetings(string $fileName, XDate $xDate): void
    {
        $employees = $this->repository->findAll();

        foreach ($employees as $employee) {
            if ($employee->isBirthday($xDate)) {
                $recipient = $employee->getEmail();
                $body = sprintf('Happy Birthday, dear %s!', $employee->getFirstName());
                $subject = 'Happy Birthday!';
                $this->sendMessage('sender@here.com', $subject, $body, $recipient);
            }
        }
    }

    private function sendMessage(string $sender, string $subject, string $body, string $recipient): void
    {
        // Create a mail session
        $this->mailer = new Mailer(new SendmailTransport());

        // Construct the message
        $msg = (new Email())
            ->from($sender)
            ->to($recipient)
            ->subject($subject)
            ->text($body);

        // Send the message
        $this->doSendMessage($msg);
    }

    // made protected for testing :-(
    protected function doSendMessage(Email $msg): void
    {
        $this->mailer->send($msg);
    }
}
