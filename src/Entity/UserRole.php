<?php

declare(strict_types=1);

namespace Atom\Entity;

enum UserRole: int
{
    case USER = 1;
    case ADMIN = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::ADMIN => 'Admin',
        };
    }
}
