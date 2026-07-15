<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Sidebar;

use Atom\Entity\UserRole;

final class SidebarMenuProvider
{
    public function getMenuItems(): array
    {
        return [
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
    }
}
