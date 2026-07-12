<?php

declare(strict_types=1);

namespace Atom\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class UserAuthKey
{
    public function __construct(
        private string $uuid,
        private string $userUuid,
        private DateTimeImmutable $expiresAt,
    ) {}

    public static function create(
        string $userUuid,
        ?string $uuid = null,
        ?DateTimeImmutable $expiresAt = null,
    ): self
    {
        return new self(
            uuid: $uuid ?: Uuid::uuid7()->toString(),
            userUuid: $userUuid,
            expiresAt: $expiresAt ?: (new DateTimeImmutable())->modify('+30 days'),
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isValid(string $userUuid): bool
    {
        return $this->userUuid === $userUuid && !$this->isExpired();
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new DateTimeImmutable();
    }
}
