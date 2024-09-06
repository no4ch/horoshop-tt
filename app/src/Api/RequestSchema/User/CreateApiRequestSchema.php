<?php

declare(strict_types=1);

namespace App\Api\RequestSchema\User;

use App\Api\RequestSchema\ApiRequestSchemaInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateApiRequestSchema implements ApiRequestSchemaInterface
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
    private string $phone;

    #[Assert\Length(
        min: 1,
        max: 8,
    )]
    private ?string $password;

    public function __construct(
        string $login,
        string $phone,
        #[\SensitiveParameter]
        ?string $password,
    ) {
        $this->login = $login;
        $this->phone = $phone;
        $this->password = $password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
