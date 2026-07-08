<?php

declare(strict_types=1);

namespace Atom\User\Service;

use Atom\User\Entity\User;
use Yiisoft\Security\PasswordHasher;

final readonly class UserService
{
    public function validatePassword(User $identity, string $password): bool
    {
        if ($identity->password === null) {
            return false;
        }

        return (new PasswordHasher())->validate($password, $identity->password);
    }
}
