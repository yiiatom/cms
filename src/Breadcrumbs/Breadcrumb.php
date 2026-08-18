<?php

declare(strict_types=1);

namespace Atom\Breadcrumbs;

final class Breadcrumb
{
    public function __construct(
        private string $label,
        private ?string $url = null,
        private bool $encode = true,
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getEncode(): bool
    {
        return $this->encode;
    }
}
