<?php

declare(strict_types=1);

namespace Atom\Sidebar\Listener;

use Atom\Entity\UserRole;
use Atom\Sidebar\Event\SidebarMenuEvent;
use Atom\Sidebar\SidebarMenuItem;
use Yiisoft\Translator\TranslatorInterface;

final class DashboardSidebarListener
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function __invoke(SidebarMenuEvent $event): void
    {
        $t = $this->translator->withDefaultCategory('atom-cms');

        $item = new SidebarMenuItem(
            label: $t->translate('Dashboard'),
            routeName: 'atom.dashboard',
            icon: 'fa-solid fa-gauge',
            requiredRole: UserRole::ADMIN,
            priority: 0,
        );

        $event->addItem($item);
    }
}
