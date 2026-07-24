<?php

declare(strict_types=1);

namespace Atom\Entity;

enum UserStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';
    case DELETED = 'deleted';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::BLOCKED => 'Blocked',
            self::DELETED => 'Deleted',
        };
    }

    public function getCssClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning text-dark',
            self::ACTIVE => 'bg-success text-white',
            self::BLOCKED => 'bg-danger text-white',
            self::DELETED => 'bg-secondary text-white',
        };
    }
}
