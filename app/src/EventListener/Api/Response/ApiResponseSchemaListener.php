<?php

declare(strict_types=1);

namespace App\EventListener\Api\Response;


use App\Api\ResponseSchema\ApiResponseInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

#[AsEventListener]
class ApiResponseSchemaListener
{
    public function __invoke(
        ViewEvent $event
    ): void {
        $controllerResult = $event->getControllerResult();

        if ($controllerResult instanceof ApiResponseInterface) {
            $responseData = [
                'message' => 'ok',
                'data' => $controllerResult->getApiResponseData(),
            ];

            $event->setResponse(new JsonResponse($responseData));
        }
    }
}
