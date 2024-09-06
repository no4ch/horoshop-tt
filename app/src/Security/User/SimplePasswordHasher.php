<?php

declare(strict_types=1);

namespace App\Security\User;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class SimplePasswordHasher implements PasswordHasherInterface
{
    public function hash(#[\SensitiveParameter] string $plainPassword): string
    {
        return md5($plainPassword);
    }

    public function verify(string $hashedPassword, #[\SensitiveParameter] string $plainPassword): bool
    {
        return $hashedPassword === $this->hash($plainPassword);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return false;
    }
}
