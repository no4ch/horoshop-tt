<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\User\SimplePasswordHasher;
use Psr\Log\LoggerInterface;

class UserCreationService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LoggerInterface $logger,
        private readonly SimplePasswordHasher $simplePasswordHasher,
        private readonly UserPasswordUpdater $userPasswordUpdater,
    ) {
    }

    // arguments can be improved via class with arguments (DTO etc.)
    public function createUser(
        string $login,
        string $phone,
        #[\SensitiveParameter] string $password,
    ): User {
        $hashedPassword = $this->simplePasswordHasher->hash($password);

        $user = $this->userRepository->findOneBy([
            'login' => $login,
            'pass' => $hashedPassword,
        ]);

        if (!$user) {
            $user = $this->userRepository->create(
                $login,
                $phone,
            );

            $this->userPasswordUpdater->updatePassword($user, $password);
        }

        try {
            $this->userRepository->saveUser($user);
        } catch (\Throwable $e) {
            $this->logger->critical('User saving failed.', [
                'exception' => (string) $e,
                'id' => $user->getId(),
                'login' => $login,
            ]);

            throw $e;
        }

        return $user;
    }
}
