<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Sidebar;

use Atom\Entity\UserRole;
use Atom\Event\SidebarMenuEvent;
use Yiisoft\Translator\TranslatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class SidebarMenuProvider
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private TranslatorInterface $translator
    ) {}

    public function getMenuItems(): array
    {
        $items = [
            new SidebarMenuItem(
                label: $this->translator->translate('Dashboard', [], 'atom-cms'),
                routeName: 'atom.dashboard',
                icon: 'fa-solid fa-gauge',
                requiredRole: UserRole::ADMIN,
            ),
            new SidebarMenuItem(
                label: $this->translator->translate('Users', [], 'atom-users'),
                routeName: 'atom.user.index',
                icon: 'fa-solid fa-users',
                requiredRole: UserRole::ADMIN,
            ),
        ];

        $event = $this->eventDispatcher->dispatch(new SidebarMenuEvent($items));

        return $event->getItems();
    }
}
