<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Api\RequestSchema\User\CreateApiRequestSchema;
use App\Api\RequestSchema\User\UpdateApiRequestSchema;
use App\Api\ResponseSchema\ApiResponseInterface;
use App\Api\ResponseSchema\EmptyApiResponseSchema;
use App\Entity\User;
use App\Services\User\UserCreationService;
use App\Services\User\UserDeletionService;
use App\Services\User\UserProvider;
use App\Services\User\UserUpdater;
use App\View\User\UserViewFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UsersController extends AbstractController
{
    #[Route(
        path: '/v1/api/users/{userId}',
        methods: Request::METHOD_GET,
    )]
    #[IsGranted(User::ROLE_USER)]
    public function indexAction(
        UserProvider $userProvider,
        UserViewFactory $userViewFactory,
        int $userId,
    ): ApiResponseInterface {
        if (!($user = $userProvider->getUserById($userId))) {
            throw new NotFoundHttpException();
        }

        return $userViewFactory->create($user);
    }

    #[Route(
        path: '/v1/api/users',
        methods: Request::METHOD_POST,
    )]
    #[IsGranted(User::ROLE_ADMIN)] // only admin can create a new user
    public function createAction(
        CreateApiRequestSchema $createApiRequestSchema,
        UserCreationService $userCreationService,
        UserViewFactory $userViewFactory,
    ): ApiResponseInterface {
        $user = $userCreationService->createUser(
            $createApiRequestSchema->getLogin(),
            $createApiRequestSchema->getPhone(),
            $createApiRequestSchema->getPassword(),
        );

        return $userViewFactory->create($user);
    }

    #[Route(
        path: '/v1/api/users/{userId}',
        methods: Request::METHOD_PUT,
    )]
    #[IsGranted(User::ROLE_USER)]
    public function updateAction(
        UserUpdater $userUpdater,
        UserViewFactory $userViewFactory,
        UpdateApiRequestSchema $updateApiRequestSchema,
        int $userId,
    ): ApiResponseInterface {
        $user = $userUpdater->updateUserById(
            $userId,
            $updateApiRequestSchema->getLogin(),
            $updateApiRequestSchema->getPhone(),
            $updateApiRequestSchema->getPassword(),
        );

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $userViewFactory->create($user);
    }

    #[Route(
        path: '/v1/api/users/{userId}',
        methods: Request::METHOD_DELETE,
    )]
    #[IsGranted(User::ROLE_ADMIN)]
    public function deleteAction(
        UserDeletionService $userDeletionService,
        int $userId,
    ): ApiResponseInterface {
        $userDeletionService->deleteUserById($userId);

        return new EmptyApiResponseSchema();
    }
}
