<?php
declare(strict_types=1);
namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

// class ProviderBEmployeeInput
// {
//     public string $first;
//     public string $last;
//     public string $email_address;
// }

class ProviderBEmployeeInput
{
    #[ApiProperty(readable: true, writable: true)]
    #[Assert\NotBlank(message: "first is required")]
    public ?string $first = null;

    #[ApiProperty(readable: true, writable: true)]
    #[Assert\NotBlank(message: "last is required")]
    public ?string $last = null;

    #[ApiProperty(readable: true, writable: true)]
    #[Assert\NotBlank(message: "email_address is required")]
    #[Assert\Email]
    public ?string $email_address = null;

    public function __toString(): string
    {
        return sprintf(
            'DTO State: first=%s, last=%s, email_address=%s',
            $this->first ?? 'null',
            $this->last ?? 'null',
            $this->email_address ?? 'null'
        );
    }
}
