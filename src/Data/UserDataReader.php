<?php

declare(strict_types=1);

namespace Atom\Data;

use Generator;
use Yiisoft\Data\Db\QueryDataReader;

class UserDataReader extends QueryDataReader
{
    final public function getIterator(): Generator
    {
        if ($this->batchSize !== null) {
            foreach ($this->getPreparedQuery()->batch($this->batchSize) as $data) {
                /** @psalm-var array<TKey, TValue> $data */
                yield from $data;
            }
            /** @infection-ignore-all */
            return;
        }

        if (is_array($this->cache)) {
            yield from $this->cache;
            return;
        }

        /** @psalm-var array<TKey, TValue> */
        $this->cache = $this->getPreparedQuery()->all();
        yield from $this->cache;
    }
}
