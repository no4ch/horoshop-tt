<?php

declare(strict_types=1);

namespace App\EventListener\Api\Request;

use App\Exception\Api\Request\ApiRequestSchemaSerializationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(
    priority: 1,
)]
class ApiRequestSchemaSerializationExceptionListener
{
    public function __invoke(
        ExceptionEvent $event
    ): void {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiRequestSchemaSerializationException) {
            $response = new JsonResponse([
                'data' => [],
                'message' => 'Your request has extra attributes or not enough attributes. Check them.'
            ], Response::HTTP_BAD_REQUEST);

            $event->setResponse($response);
        }
    }
}
