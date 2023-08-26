<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mime\Email;

class BirthdayService
{
    private Mailer $mailer;

    public function sendGreetings(string $fileName, XDate $xDate): void
    {
        $fileHandler = fopen($fileName, 'r');
        fgetcsv($fileHandler);

        while ($employeeData = fgetcsv($fileHandler)) {
            $employeeData = array_map('trim', $employeeData);
            $employee = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
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
