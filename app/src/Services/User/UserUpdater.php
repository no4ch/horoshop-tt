<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;

class UserUpdater
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserProvider $userProvider,
        private readonly UserPasswordUpdater $userPasswordUpdater,
        private readonly LoggerInterface $logger,
    ) {
    }

    // arguments can be improved via class with arguments (DTO etc.)
    public function updateUserById(
        int $userId,
        string $login,
        string $phone,
        #[\SensitiveParameter] string $password,
    ): ?User {
        $user = $this->userProvider->getUserById($userId);

        if ($user) {
            $user->setLogin($login);
            $user->setPhone($phone);
            $this->userPasswordUpdater->updatePassword($user, $password);

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
        }

        return $user;
    }
}
