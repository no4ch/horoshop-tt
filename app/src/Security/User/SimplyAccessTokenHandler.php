<?php

declare(strict_types=1);

namespace App\Security\User;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class SimplyAccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private readonly SimpleTokenProvider $simpleTokenProvider,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        try {
            $isTokenValid = $this->simpleTokenProvider->validateToken($accessToken);
            $userId = $this->simpleTokenProvider->getUserIdFromToken($accessToken);

            if (!$isTokenValid || !$userId) {
                throw new \InvalidArgumentException('Given token is invalid.');
            }

            return new UserBadge((string) $userId);
        } catch (\Throwable $e) {
            $this->logger->info('Authentication error.', [
                'exception' => (string) $e,
            ]);

            throw new BadCredentialsException('Invalid credentials.');
        }
    }
}
