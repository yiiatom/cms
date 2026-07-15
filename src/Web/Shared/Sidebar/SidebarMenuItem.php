<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Sidebar;

use Atom\Entity\User;
use Atom\Entity\UserRole;

final class SidebarMenuItem
{
    public function __construct(
        private string $label,
        private string $routeName,
        private string $icon,
        private ?UserRole $requiredRole = null,
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }


    public function isVisibleForUser(?User $user): bool
    {
        if ($this->requiredRole === null) {
            return true;
        }

        return $user !== null && $user->canAccess($this->requiredRole);
    }

    public function isActive(string $currentRouteName): bool
    {
        if ($this->routeName === $currentRouteName) {
            return true;
        };

        $itemParts = explode('.', $this->routeName);
        $currentParts = explode('.', $currentRouteName);

        if (count($itemParts) < 2 || count($currentParts) < 2) {
            return false;
        }

        return $itemParts[0] === $currentParts[0] && $itemParts[1] === $currentParts[1];
    }
}
