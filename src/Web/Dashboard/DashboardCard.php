<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

final class DashboardCard
{
    public function __construct(
        public string $title,
        public string $value,
        public string $icon,
        public string $bgClass,
        public int $order = 100,
    ) {}
}
