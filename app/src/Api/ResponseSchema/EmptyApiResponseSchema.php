<?php

declare(strict_types=1);

namespace App\Api\ResponseSchema;

class EmptyApiResponseSchema implements ApiResponseInterface
{
    public function getApiResponseData(): array
    {
        return [];
    }
}
