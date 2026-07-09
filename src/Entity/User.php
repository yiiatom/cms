<?php

declare(strict_types=1);

namespace Atom\Entity;

use DateTimeImmutable;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\User\Login\Cookie\CookieLoginIdentityInterface;

final class User implements IdentityInterface, CookieLoginIdentityInterface
{
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;
    const STATUS_ARCHIVED = 3;

    public function __construct(
        public string $uuid,
        public string $username,
        public ?string $email,
        public ?string $password,
        public ?DateTimeImmutable $passwordExpiresAt,
        public ?string $token,
        public ?string $authKey,
        public int $status,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $avatarUrl,
        public ?DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $loginAt,
        public ?string $loginIp,
    ) {}

    public function getId(): string
    {
        return $this->uuid;
    }

    public function getCookieLoginKey(): string
    {
        return $this->authKey;
    }

    public function validateCookieLoginKey(string $key): bool
    {
        return $this->authKey === $key;
    }
}
