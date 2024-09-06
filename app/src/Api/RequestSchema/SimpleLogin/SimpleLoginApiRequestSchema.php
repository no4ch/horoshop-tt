<?php

declare(strict_types=1);

namespace App\Api\RequestSchema\SimpleLogin;

use App\Api\RequestSchema\ApiRequestSchemaInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SimpleLoginApiRequestSchema implements ApiRequestSchemaInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 8,
    )]
    private string $login;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 8,
    )]
    private string $password;

    public function __construct(
        string $login,
        #[\SensitiveParameter]
        string $password,
    ) {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
