<?php

declare(strict_types=1);

use Atom\Identity\IdentityRepository;
use Atom\Security\PasswordHasherAdepter;
use Atom\Security\PasswordHasherInterface;
use Atom\Web\Dashboard\DashboardService;
use Yiisoft\Auth\IdentityRepositoryInterface;

return [
    IdentityRepositoryInterface::class => IdentityRepository::class,
    PasswordHasherInterface::class => PasswordHasherAdepter::class,
    DashboardService::class => [
        '__construct()' => [
            'appEnv' => $params['atom.env'],
            'appDebug' => $params['atom.debug'],
        ]
    ],
];
