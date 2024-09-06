<?php

declare(strict_types=1);

namespace App\ValueResolver\Api;

use App\Api\RequestSchema\ApiRequestSchemaInterface;
use App\Exception\Api\Request\ApiRequestSchemaSerializationException;
use App\Exception\Api\Request\ApiRequestSchemaValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiRequestSchemaValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $requestSchemaClass = $argument->getType();

        if (!is_a($argument->getType(), ApiRequestSchemaInterface::class, true)) {
            return [];
        }

        $data = $request->getContent();

        try {
            $requestSchema = $this->serializer->deserialize(
                $data,
                $requestSchemaClass,
                JsonEncoder::FORMAT,
                [
                    AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                ]
            );
        } catch (\Throwable $e) {
            throw new ApiRequestSchemaSerializationException();
        }

        $errors = $this->validator->validate($requestSchema);
        if ($errors->count()) {
            throw new ApiRequestSchemaValidationException($errors);
        }

        return [$requestSchema];
    }
}
