<?php

declare(strict_types=1);

use Atom\Security\PasswordHasherAdepter;
use Atom\Security\PasswordHasherInterface;

return [
    PasswordHasherInterface::class => PasswordHasherAdepter::class,
];
