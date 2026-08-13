<?php

declare(strict_types=1);

namespace Atom\Entity;

enum UserStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::BLOCKED => 'Blocked',
        };
    }

    public function getCssClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning text-dark',
            self::ACTIVE => 'bg-success text-white',
            self::BLOCKED => 'bg-danger text-white',
        };
    }
}
