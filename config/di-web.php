<?php

declare(strict_types=1);

use Atom\User\Data\UserRepository;
use Yiisoft\Auth\IdentityRepositoryInterface;

return [
    IdentityRepositoryInterface::class => UserRepository::class,
];
