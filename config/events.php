<?php

declare(strict_types=1);

use Atom\Dashboard\Event\DashboardEvent;
use Atom\Dashboard\Listener\SystemHealthListener;
use Atom\Dashboard\Listener\UsersSummaryListener;
use Atom\Sidebar\Event\SidebarMenuEvent;
use Atom\Sidebar\Listener\DashboardSidebarListener;
use Atom\Sidebar\Listener\UserSidebarListener;

return [
    DashboardEvent::class => [
        [SystemHealthListener::class, '__invoke'],
        [UsersSummaryListener::class, '__invoke'],
    ],

    SidebarMenuEvent::class => [
        [DashboardSidebarListener::class, '__invoke'],
        [UserSidebarListener::class, '__invoke'],
    ],
];
