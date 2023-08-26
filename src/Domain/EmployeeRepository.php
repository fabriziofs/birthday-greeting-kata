<?php

namespace Domain;

use Employee;

interface EmployeeRepository
{
    /** @return Employee[] */
    public function findAll(): array;
}
