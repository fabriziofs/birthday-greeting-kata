<?php

use Domain\Email;
use Domain\EmailSender;
use Domain\EmployeeRepository;

class BirthdayService
{
    private EmployeeRepository $repository;
    private EmailSender $emailSender;

    public function __construct(EmployeeRepository $repository, EmailSender $emailSender)
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
                $this->doSendMessage(new Email('sender@here.com', $subject, $body, $recipient));
            }
        }
    }

    // made protected for testing :-(
    protected function doSendMessage(Email $msg): void
    {
        $this->emailSender->send($msg);
    }
}
