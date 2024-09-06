<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Entity\User;
use App\Security\User\SimplePasswordHasher;

class UserPasswordUpdater
{
    public function __construct(
        private readonly SimplePasswordHasher $simplePasswordHasher,
    ) {
    }

    public function updatePassword(
        User $user,
        #[\SensitiveParameter] string $plainPassword,
    ): void {
        $hashedPassword = $this->simplePasswordHasher->hash($plainPassword);

        $user->setPassword($hashedPassword);
    }
}
