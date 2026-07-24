<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

final class DashboardCard
{
    /**
     * @param DashboardCardItem[] $items
     */
    public function __construct(
        public string $title,
        public string $icon,
        public array $items,
        public int $order = 100,
        public ?string $linkUrl = null,
        public ?string $linkText = null,
    ) {}
}
