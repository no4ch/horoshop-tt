<?php

declare(strict_types=1);

namespace App\View\SimpleLogin;

use App\Api\ResponseSchema\ApiResponseInterface;

readonly class SimpleLoginView implements ApiResponseInterface
{
    public function __construct(
        private string $token
    ) {
    }

    public function getApiResponseData(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
