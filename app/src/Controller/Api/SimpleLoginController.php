<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Api\RequestSchema\SimpleLogin\SimpleLoginApiRequestSchema;
use App\Api\ResponseSchema\ApiResponseInterface;
use App\Services\Api\SimpleLoginService;
use App\View\SimpleLogin\SimpleLoginViewFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class SimpleLoginController extends AbstractController
{
    #[Route(
        path: '/api/login',
        methods: Request::METHOD_POST,
    )]
    public function index(
        SimpleLoginService $simpleLoginService,
        SimpleLoginApiRequestSchema $simpleLoginApiRequestSchema,
        SimpleLoginViewFactory $simpleLoginViewFactory,
    ): ApiResponseInterface {
        $token = $simpleLoginService->getTokenByCredentials(
            $simpleLoginApiRequestSchema->getLogin(),
            $simpleLoginApiRequestSchema->getPassword(),
        );

        if (!$token) {
            throw new BadRequestHttpException('Invalid username or password.');
        }

        return $simpleLoginViewFactory->create($token);
    }
}
