<?php

declare(strict_types=1);

namespace Atom\Data;

use Generator;
use Atom\Entity\User;
use Atom\Mapper\UserMapper;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Data\Reader\ReadableDataInterface;

class UserDataReader implements ReadableDataInterface
{
    public function __construct(
        private QueryDataReader $dataReader,
        private UserMapper $mapper,
    ) {}

    final public function read(): Generator
    {
        foreach ($this->dataReader->read() as $row) {
            yield $this->mapper->mapRowToEntity($row);
        }
    }

    final public function readOne(): User|null
    {
        $row = $this->dataReader->readOne();
        return $this->mapper->mapRowToEntity($row);
    }
}
