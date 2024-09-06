<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserProvider
{
    public function __construct(
        private readonly Security $security,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function getUserById(
        int $userId,
    ): ?User {
        $user = null;

        // can be improved via providers for current role, etc.
        if ($securityUser = $this->security->getUser()) {
            if (in_array(User::ROLE_ADMIN, $securityUser->getRoles())) {
                $user = $this->userRepository->findOneBy([
                    'id' => $userId,
                ]);
            } else {
                if ($securityUser instanceof User) {
                    if ($securityUser->getId() === $userId) {
                        $user = $securityUser;
                    }
                }
            }
        }

        return $user;
    }
}
