<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(
    priority: -129,
)]
class DefaultExceptionListener
{
    public function __invoke(
        ExceptionEvent $event,
    ): void {
        $response = new JsonResponse([
            'message' => 'Server error.',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);

        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        $event->setResponse($response);
    }
}
