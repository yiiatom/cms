<?php

declare(strict_types=1);

namespace Atom\Entity;

enum UserRole: int
{
    case USER = 10;
    case ADMIN = 50;

    public function getLabel(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::ADMIN => 'Admin',
        };
    }
}
