<?php

declare(strict_types=1);

namespace Atom\Data;

use Generator;
use Atom\Data\UserRepository;
use Atom\Entity\User;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Data\Reader\ReadableDataInterface;

class UserDataReader implements ReadableDataInterface
{
    public function __construct(
        private QueryDataReader $dataReader
    ) {}

    final public function read(): Generator
    {
        foreach ($this->dataReader->read() as $row) {
            yield UserRepository::createEntity($row);
        }
    }

    final public function readOne(): User|null
    {
        $row = $this->dataReader->readOne();
        return UserRepository::createEntity($row);
    }
}
