<?php

declare(strict_types=1);

namespace Atom\Entity;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';

    public function getLabel(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::ADMIN => 'Admin',
        };
    }

    public function getCssClass(): string
    {
        return match ($this) {
            self::USER => 'bg-secondary text-white',
            self::ADMIN => 'bg-danger text-white',
        };
    }

    public function canAccess(UserRole $role): bool
    {
        return $this === $role || $this === UserRole::ADMIN;
    }
}
