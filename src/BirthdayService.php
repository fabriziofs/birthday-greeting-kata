<?php

use Domain\Email;
use Domain\EmailSender;
use Domain\EmployeeRepository;
use Infrastructure\SymfonyEmailSender;

class BirthdayService
{
    private EmployeeRepository $repository;
    private EmailSender $emailSender;

    public function __construct(EmployeeRepository $repository, EmailSender $emailSender = new SymfonyEmailSender())
    {
        $this->repository = $repository;
        $this->emailSender = $emailSender;
    }

    public function sendGreetings(XDate $xDate): void
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
        $this->doSendMessage(new Email($sender, $subject, $body, $recipient));
    }

    // made protected for testing :-(
    protected function doSendMessage(Email $msg): void
    {
        $this->emailSender->send($msg);
    }
}
