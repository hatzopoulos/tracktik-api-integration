<?php

namespace App\Service;

use App\Dto\ProviderAEmployeeInput;
use App\Dto\ProviderBEmployeeInput;
use App\Entity\Employee;

class EmployeeMapperService
{
    public function mapFromProviderA(ProviderAEmployeeInput $input): Employee
    {
        $employee = new Employee();
        $employee->setFirstName($input->given_name);
        $employee->setLastName($input->family_name);
        $employee->setEmail($input->email);
        return $employee;
    }

    public function mapFromProviderB(ProviderBEmployeeInput $input): Employee
    {
        $employee = new Employee();
        $employee->setFirstName($input->first);
        $employee->setLastName($input->last);
        $employee->setEmail($input->email_address);
        return $employee;
    }
}
