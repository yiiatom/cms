<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Breadcrumbs;

final class Breadcrumb
{
    public function __construct(
        private string $label,
        private ?string $routeName = null,
        private array $routeArguments = [],
        private array $routeQueryParameters = [],
        private ?string $routeHash = null,
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function getRouteArguments(): array
    {
        return $this->routeArguments;
    }

    public function getRouteQueryParameters(): array
    {
        return $this->routeQueryParameters;
    }

    public function getRouteHash(): ?string
    {
        return $this->routeHash;
    }

    public function isLink(): bool
    {
        return $this->routeName !== null;
    }
}
