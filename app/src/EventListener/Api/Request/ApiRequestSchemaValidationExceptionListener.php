<?php

declare(strict_types=1);

namespace App\EventListener\Api\Request;

use App\Exception\Api\Request\ApiRequestSchemaValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(
    priority: 1,
)]
class ApiRequestSchemaValidationExceptionListener
{
    public function __invoke(
        ExceptionEvent $event
    ): void {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiRequestSchemaValidationException) {
            $exceptionErrors = $exception->getErrors();
            $errors = [];

            foreach ($exceptionErrors as $exceptionError) {
                $errors[] = [
                    'property' => $exceptionError->getPropertyPath(),
                    'message' => $exceptionError->getMessage(),
                ];
            }

            $response = new JsonResponse([
                'message' => 'Validation error.',
                'errors' => $errors,
            ], JsonResponse::HTTP_BAD_REQUEST);

            $event->setResponse($response);
        }
    }
}
