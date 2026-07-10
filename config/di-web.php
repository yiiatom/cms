<?php

declare(strict_types=1);

use Atom\Identity\IdentityRepository;
use Atom\Security\PasswordHasherAdepter;
use Atom\Security\PasswordHasherInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

return [
    IdentityRepositoryInterface::class => IdentityRepository::class,
    PasswordHasherInterface::class => PasswordHasherAdepter::class,
];
