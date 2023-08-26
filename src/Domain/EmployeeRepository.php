<?php

namespace Domain;

interface EmployeeRepository
{
    /** @return Employee[] */
    public function findAll(): array;
}
