<?php

use Application\BirthdayService;
use Domain\XDate;
use Infrastructure\FileSystemEmployeeRepository;
use Infrastructure\SymfonyEmailSender;

$service = new BirthdayService(new FileSystemEmployeeRepository(), new SymfonyEmailSender());
$service->sendGreetings(new XDate('2008/10/08'));
