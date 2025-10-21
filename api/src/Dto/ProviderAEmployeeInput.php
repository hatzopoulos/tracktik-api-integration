<?php

namespace App\Dto;

class ProviderAEmployeeInput
{
    public function __construct(
        public ?string $given_name = null,
        public ?string $family_name = null,
        public ?string $email = null,
        // public ?string $employee_id = null
    ) {}
}