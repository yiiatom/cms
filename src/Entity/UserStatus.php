<?php

declare(strict_types=1);

namespace Atom\Entity;

enum UserStatus: int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case BLOCKED = 2;
    case ARCHIVED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::BLOCKED => 'Blocked',
            self::ARCHIVED => 'Archived',
        };
    }
}
