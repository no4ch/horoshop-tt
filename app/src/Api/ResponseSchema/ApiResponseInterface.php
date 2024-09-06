<?php

declare(strict_types=1);

namespace App\Api\ResponseSchema;

interface ApiResponseInterface
{
    /**
     * Can be better returning concrete schema class for serialization
     *
     * @return array<string, mixed>
     */
    public function getApiResponseData(): array;
}
