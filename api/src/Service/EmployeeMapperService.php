<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use App\Dto\ProviderAEmployeeInput;
use App\Dto\ProviderBEmployeeInput;
use App\Entity\Employee;

class EmployeeMapperService
{
    public function __construct(
        private ?LoggerInterface $logger = null
    ) {}

    public function mapFromProviderA(ProviderAEmployeeInput $input): Employee
    {
        $employee = new Employee();
        $employee->setFirstName($input->given_name);
        $employee->setLastName($input->family_name);
        $employee->setEmail($input->email);

        $this->logger->debug('Checking mapFromProviderA.', [
                'given_name' => $input->given_name,
                'family_name' => $input->family_name,
                'email' => $input->email,
                // 'givenName' => $input->givenName,
                // 'familyName' => $input->familyName,
                // 'email' => $input->email
            ]);

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
