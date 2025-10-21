<?php

namespace App\Service;

use App\Entity\Employee;

class EmployeeMapperService
{
    public function mapFromProviderA(array $data): Employee
    {
        $employee = new Employee();
        $employee->setFirstName($data['given_name'] ?? '');
        $employee->setLastName($data['family_name'] ?? '');
        $employee->setEmail($data['email'] ?? '');
        return $employee;
    }

    public function mapFromProviderB(array $data): Employee
    {
        $employee = new Employee();
        $employee->setFirstName($data['first'] ?? '');
        $employee->setLastName($data['last'] ?? '');
        $employee->setEmail($data['email_address'] ?? '');
        return $employee;
    }
}
