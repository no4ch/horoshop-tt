<?php

declare(strict_types=1);

namespace App\View\User;

use App\Api\ResponseSchema\ApiResponseInterface;
use App\Entity\User;

class UserView implements ApiResponseInterface
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public function getApiResponseData(): array
    {
        return [
            'id' => $this->user->getId(),
            'login' => $this->user->getLogin(),
            'phone' => $this->user->getPhone(),
            'pass' => $this->user->getPassword(),
        ];
    }
}
