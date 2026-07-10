<?php

declare(strict_types=1);

namespace Atom\Security;

use Yiisoft\Security\PasswordHasher;

final class PasswordHasherAdepter implements PasswordHasherInterface
{
    public function __construct(
        private PasswordHasher $passwordHasher
    ) {
    }

    public function hash(string $password): string
    {
        return $this->passwordHasher->hash($password);
    }

    public function validate(string $password, string $hash): bool
    {
        return $this->passwordHasher->validate($password, $hash);
    }
}
