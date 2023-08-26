<?php

declare(strict_types=1);

namespace Infrastructure;

use Domain\Employee;
use Domain\EmployeeRepository;

final class FileSystemEmployeeRepository implements EmployeeRepository
{
    private string $fileName;

    public function __construct()
    {
        $this->fileName = __DIR__ . '/../../test/resources/employee_data.txt';
    }

    public function findAll(): array
    {
        $fileHandler = fopen($this->fileName, 'r');
        fgetcsv($fileHandler);

        $employees = [];
        while ($employeeData = fgetcsv($fileHandler)) {
            $employeeData = array_map('trim', $employeeData);
            $employees[] = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
        }

        return $employees;
    }
}
