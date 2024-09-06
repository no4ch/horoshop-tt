<?php

declare(strict_types=1);

namespace App\Security\User;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SimpleTokenProvider
{
    private const int ONE_DAY_TOKEN_LIFETIME = 86400;

    private const string ID_KEY = 'id';

    private const string EXPIRE_KEY = 'expires';

    public function __construct(
        #[Autowire(env: 'APP_SECRET')] private readonly string $secretKey
    ) {
    }

    // can be better returning some TokenInterface, with full token data
    public function createToken(User $user): string
    {
        if ($user->getId() === null) {
            throw new \InvalidArgumentException('User must have an id.');
        }

        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR);
        $payload = json_encode([
            // can be improved via hashing etc.
            self::ID_KEY => $user->getId(),
            self::EXPIRE_KEY => time() + self::ONE_DAY_TOKEN_LIFETIME,
        ], JSON_THROW_ON_ERROR);

        $base64UrlHeader = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function validateToken(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignatureCheck = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        if ($base64UrlSignature !== $base64UrlSignatureCheck) {
            return false;
        }

        $payload = json_decode(base64_decode($base64UrlPayload), true, 512, JSON_THROW_ON_ERROR);

        return isset($payload[self::EXPIRE_KEY]) && ($payload[self::EXPIRE_KEY] > time());
    }

    public function getUserIdFromToken(string $token): ?int
    {
        $userId = null;

        $parts = explode('.', $token);
        if (count($parts) === 3) {
            if ($this->validateToken($token)) {
                $payload = json_decode(base64_decode($parts[1]), true, 512, JSON_THROW_ON_ERROR);

                $userId = $payload[self::ID_KEY] ?? null;
            }
        }

        return $userId;
    }
}
