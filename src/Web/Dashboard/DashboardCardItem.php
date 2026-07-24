<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

final class DashboardCardItem
{
    public function __construct(
        public string $label,
        public string $value,
        public string $status = 'default',
    ) {}
}
