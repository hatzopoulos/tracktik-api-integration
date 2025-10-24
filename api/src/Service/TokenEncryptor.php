<?php
namespace App\Service;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class TokenEncryptor
{
    private Key $key;

    public function __construct(string $encryptionKey)
    {
        $this->key = Key::loadFromAsciiSafeString($encryptionKey);
    }

    public function encrypt(?string $plain): ?string
    {
        return $plain ? Crypto::encrypt($plain, $this->key) : null;
    }

    public function decrypt(?string $cipher): ?string
    {
        return $cipher ? Crypto::decrypt($cipher, $this->key) : null;
    }
}
