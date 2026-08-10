<?php

declare(strict_types=1);

namespace Atom\Data;

use Generator;
use Atom\Entity\User;
use Atom\Mapper\UserMapper;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\FilterInterface;
use Yiisoft\Data\Reader\Sort;

class UserDataReader implements DataReaderInterface
{
    public function __construct(
        private DataReaderInterface $dataReader,
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

    final public function withLimit(?int $limit): static
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withLimit($limit);
        return $new;
    }

    final public function getLimit(): ?int
    {
        return $this->dataReader->getLimit();
    }

    final public function withOffset(?int $offset): static
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withOffset($offset);
        return $new;
    }

    final public function getOffset(): int
    {
        return $this->dataReader->getOffset();
    }

    final public function count(): int
    {
        return $this->dataReader->count();
    }

    final public function withSort(?Sort $sort): static
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withSort($sort);
        return $new;
    }

    final public function getSort(): ?Sort
    {
        return $this->dataReader->getSort();
    }

    final public function withFilter(FilterInterface $filter): static
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withFilter($filter);
        return $new;
    }

    final public function getFilter(): FilterInterface
    {
        return $this->dataReader->getFilter();
    }

    final public function getIterator(): Generator
    {
        return $this->read();
    }
}
