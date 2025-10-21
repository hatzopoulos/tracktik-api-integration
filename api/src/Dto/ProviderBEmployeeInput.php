<?php

namespace App\Dto;

class ProviderBEmployeeInput
{
    public function __construct(
        public ?string $first = null,
        public ?string $last = null,
        public ?string $email_address = null,
        // public ?string $externalId = null
    ) {}
}