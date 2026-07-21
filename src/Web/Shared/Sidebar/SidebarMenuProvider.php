<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Sidebar;

use Atom\Entity\UserRole;
use Atom\Event\SidebarMenuEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

final class SidebarMenuProvider
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function getMenuItems(): array
    {
        $items = [
            new SidebarMenuItem(
                label: 'Dashboard',
                routeName: 'atom.dashboard',
                icon: 'fa-solid fa-gauge',
                requiredRole: UserRole::ADMIN,
            ),
            new SidebarMenuItem(
                label: 'Users',
                routeName: 'atom.user.index',
                icon: 'fa-solid fa-users',
                requiredRole: UserRole::ADMIN,
            ),
        ];

        $event = $this->eventDispatcher->dispatch(new SidebarMenuEvent($items));

        return $event->getItems();
    }
}
