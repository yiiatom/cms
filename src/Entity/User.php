<?php

declare(strict_types=1);

namespace Atom\Entity;

use DateTimeImmutable;
use Atom\Security\PasswordHasherInterface;
use Ramsey\Uuid\Uuid;

final class User
{
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;
    const STATUS_ARCHIVED = 3;

    private function __construct(
        public string $uuid,
        public string $username,
        public ?string $email,
        public ?string $password,
        public ?DateTimeImmutable $passwordExpiresAt,
        public int $status,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $avatarUrl,
        public ?DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
        public ?DateTimeImmutable $deletedAt,
    ) {}

    public static function create(
        string $username,
        ?string $uuid = null,
        ?string $email = null,
        ?string $password = null,
        ?DateTimeImmutable $passwordExpiresAt = null,
        ?int $status = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $avatarUrl = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $deletedAt = null,
    ): self {
        return new self(
            uuid: $uuid ?: Uuid::uuid7()->toString(),
            username: $username,
            email: $email,
            password: $password,
            passwordExpiresAt: $passwordExpiresAt,
            status: $status ?? self::STATUS_PENDING,
            firstName: $firstName,
            lastName: $lastName,
            avatarUrl: $avatarUrl,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
        );
    }

    public function validatePassword(string $password, PasswordHasherInterface $passwordHasher): bool
    {
        return $passwordHasher->validate($password, $this->password);
    }

    public function changePassword(string $password, PasswordHasherInterface $passwordHasher): void
    {
        $this->password = $passwordHasher->hash($password);
        $this->passwordExpiresAt = null;
    }
}
