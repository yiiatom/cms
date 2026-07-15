<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Breadcrumbs;

final class BreadcrumbsProvider
{
    /** @var Breadcrumb[] */
    private array $items = [];

    public function add(
        string $label,
        ?string $routeName = null,
        array $routeArguments = [],
        array $routeQueryParameters = [],
        ?string $routeHash = null,
    ): self
    {
        $this->items[] = new Breadcrumb($label, $routeName, $routeArguments, $routeQueryParameters, $routeHash);
        return $this;
    }

    /**
     * @return BreadcrumbItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
