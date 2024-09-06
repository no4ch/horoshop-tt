<?php

declare(strict_types=1);

namespace App\Services\Api;

use App\Repository\UserRepository;
use App\Security\User\SimplePasswordHasher;
use App\Security\User\SimpleTokenProvider;

class SimpleLoginService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SimplePasswordHasher $simplePasswordHasher,
        private readonly SimpleTokenProvider $simpleTokenProvider,
    ) {
    }

    public function getTokenByCredentials(
        string $login,
        #[\SensitiveParameter] string $plainPassword
    ): ?string {
        $token = null;

        $hashedPassword = $this->simplePasswordHasher->hash($plainPassword);

        $user = $this->userRepository->findOneBy([
            'login' => $login,
            'pass' => $hashedPassword,
        ]);

        if ($user) {
            $token = $this->simpleTokenProvider->createToken($user);
        }

        return $token;
    }
}
