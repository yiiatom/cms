<?php

declare(strict_types=1);

namespace Atom\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class Setting
{
    public function __construct(
        private string $uuid,
        private string $groupUuid,
        private string $key,
        private string $name,
        private int $position,
        private SettingType $type,
        private bool|int|float|string|null $value,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        SettingGroup $group,
        string $name,
        string $key,
        SettingType $type,
    ) {
        $date = new DateTimeImmutable;
        return new self(
            uuid: Uuid::uuid7()->toString(),
            groupUuid: $group->getUuid(),
            key: $key,
            name: $name,
            position: 0,
            type: $type,
            value: null,
            createdAt: $date,
            updatedAt: $date,
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getGroupUuid(): string
    {
        return $this->groupUuid;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $value): self
    {
        $this->key = $value;
        $this->updatedAt = new DateTimeImmutable;

        return $this;
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

    public function getType(): SettingType
    {
        return $this->type;
    }

    public function setType(SettingType $value): self
    {
        $this->type = $value;
        $this->updatedAt = new DateTimeImmutable;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
