<?php

declare(strict_types=1);

namespace Atom\Breadcrumbs;

final class BreadcrumbsProvider
{
    /** @var Breadcrumb[] */
    private array $breadcrumbs = [];

    public function add(Breadcrumb ...$breadcrumbs): void
    {
        foreach ($breadcrumbs as $breadcrumb) {
            $this->breadcrumbs[] = $breadcrumb;
        }
    }

    /**
     * @return Breadcrumb[]
     */
    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }
}
