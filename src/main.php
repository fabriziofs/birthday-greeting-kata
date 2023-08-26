<?php

use Infrastructure\FileSystemEmployeeRepository;

$service = new BirthdayService(new FileSystemEmployeeRepository());
$service->sendGreetings(new XDate('2008/10/08'));
