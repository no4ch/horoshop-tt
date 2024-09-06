<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Repository\UserRepository;

class UserDeletionService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    // argument can be improved via class with arguments (DTO etc.)
    public function deleteUserById(
        int $userId
    ): void {
        $user = $this->userRepository->findOneBy([
            'id' => $userId,
        ]);

        if ($user) {
            // yes, you can delete yourself
            $this->userRepository->deleteUser($user);
        }
    }
}
