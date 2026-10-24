<?php

declare(strict_types=1);

namespace Atom\Data;

use Generator;
use Atom\Entity\Setting;
use Atom\Entity\SettingGroup;
use Yiisoft\Data\Reader\ReadableDataInterface;

final readonly class SettingDataReader implements ReadableDataInterface
{
    /**
     * @psalm-param array<Setting, SettingGroup> $items
     */
    public function __construct(
        private array $items,
    ) {}

    public function read(): Generator
    {
        foreach ($this->items as $item) {
            yield $item;
        }
    }

    public function readOne(): ?object
    {
        return current($this->items);
    }
}
