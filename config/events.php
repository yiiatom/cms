<?php

declare(strict_types=1);

use Atom\Dashboard\Event\DashboardEvent;
use Atom\Dashboard\Listener\SystemHealthListener;
use Atom\Dashboard\Listener\UsersSummaryListener;

return [
    DashboardEvent::class => [
        [SystemHealthListener::class, '__invoke'],
        [UsersSummaryListener::class, '__invoke'],
    ],
];
