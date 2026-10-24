<?php

declare(strict_types=1);

namespace Atom\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class SettingGroup
{
    public function __construct(
        private string $uuid,
        private string $name,
        private int $position,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        string $name,
    ): self {
        $date = new DateTimeImmutable;
        return new self(
            uuid: Uuid::uuid7()->toString(),
            name: $name,
            position: 0,
            createdAt: $date,
            updatedAt: $date,
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): self
    {
        $this->name = $value;
        $this->updatedAt = new DateTimeImmutable;

        return $this;
    }

    public function setPosition(int $value): self
    {
        $this->position = $value;
        $this->updatedAt = new DateTimeImmutable;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
