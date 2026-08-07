<?php

declare(strict_types=1);

namespace Atom\Helper;

use Generator;
use Yiisoft\Data\Reader\ReadableDataInterface;

final readonly class ReadableDataProxy implements ReadableDataInterface
{
    public function __construct(
        private ReadableDataInterface $dataReader
    ) {}

    public function read(): Generator
    {
        yield from $this->dataReader->read();
    }

    public function readOne(): object|array|null
    {
        return $this->dataReader->readOne();
    }
}
