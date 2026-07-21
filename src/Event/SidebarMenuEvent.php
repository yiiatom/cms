<?php

declare(strict_types=1);

namespace Atom\Event;

use Atom\Web\Shared\Sidebar\SidebarMenuItem;

final class SidebarMenuEvent
{
    /**
     * @param SidebarMenuItem[] $items
     */
    public function __construct(
        private array $items = []
    ) {}

    public function addItem(SidebarMenuItem $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @return SidebarMenuItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
