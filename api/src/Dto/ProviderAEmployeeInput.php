<?php
declare(strict_types=1);
namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class ProviderAEmployeeInput
{
    #[ApiProperty(readable: true, writable: true)]
    #[Assert\NotBlank(message: "given_name is required")]
    public ?string $given_name = null;

    #[ApiProperty(readable: true, writable: true)]
    #[Assert\NotBlank(message: "family_name is required")]
    public ?string $family_name = null;

    #[ApiProperty(readable: true, writable: true)]
    #[Assert\NotBlank(message: "email is required")]
    #[Assert\Email]
    public ?string $email = null;

    public function __toString(): string
    {
        return sprintf(
            'DTO State: given_name=%s, family_name=%s, email=%s',
            $this->given_name ?? 'null',
            $this->family_name ?? 'null',
            $this->email ?? 'null'
        );
    }
}
