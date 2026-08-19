<?php

declare(strict_types=1);

namespace Atom\Sidebar;

use Atom\Entity\UserRole;
use Atom\Sidebar\Event\SidebarMenuEvent;
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
        $event = $this->eventDispatcher->dispatch(new SidebarMenuEvent());

        return $event->getItems();
    }
}
