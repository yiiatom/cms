<?php

declare(strict_types=1);

namespace Atom\Sidebar\Listener;

use Atom\Entity\UserRole;
use Atom\Sidebar\Event\SidebarMenuEvent;
use Atom\Sidebar\SidebarMenuItem;
use Yiisoft\Translator\TranslatorInterface;

final class UserSidebarListener
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function __invoke(SidebarMenuEvent $event): void
    {
        $t = $this->translator->withDefaultCategory('atom-users');

        $item = new SidebarMenuItem(
            label: $t->translate('Users'),
            routeName: 'atom.user.index',
            icon: 'fa-solid fa-users',
            requiredRole: UserRole::ADMIN,
        );

        $event->addItem($item);
    }
}
