<?php

declare(strict_types=1);

namespace App\Exception\Api\Request;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiRequestSchemaValidationException extends \Exception
{
    public function __construct(
        private readonly ConstraintViolationListInterface $errors,
    ) {
        parent::__construct();
    }

    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
