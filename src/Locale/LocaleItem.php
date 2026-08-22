<?php

declare(strict_types=1);

namespace Atom\Locale;

final class LocaleItem
{
    public function __construct(
        private readonly string $code,
        private readonly string $label,
        private readonly bool $isActive,
    ) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
