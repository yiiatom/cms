<?php

declare(strict_types=1);

namespace Atom\Repository;

use Atom\Entity\Setting;
use Atom\Mapper\SettingMapper;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class SettingRepository
{
    public function __construct(
        private ConnectionInterface $connection,
        private SettingMapper $mapper,
    ) {}

    public function exists(string $uuid): bool
    {
        return $this->connection->createQuery()
            ->from('{{%setting}}')
            ->where(['uuid' => $uuid])
            ->exists();
    }

    public function save(Setting $entity): void
    {
        if ($this->exists($entity->getUuid())) {
            $this->update($entity);
        } else {
            $this->insert($entity);
        }
    }

    public function insert(Setting $entity): void
    {
        $position = $this->getNextPosition($entity);
        $entity->setPosition($position);

        $row = $this->mapper->mapEntityToRow($entity);
        $this->connection->createCommand()
            ->insert('{{%setting}}', $row)
            ->execute();
    }

    public function update(Setting $entity): void
    {
        $row = $this->mapper->mapEntityToRow($entity);
        $this->connection->createCommand()
            ->update('{{%setting}}', $row, ['uuid' => $entity->getUuid()])
            ->execute();
    }

    public function delete(Setting $entity): int
    {
        return $this->connection->createCommand()
            ->delete('{{%setting}}', ['uuid' => $entity->getUuid()])
            ->execute();
    }

    private function createEntity(?array $row): ?Setting
    {
        if ($row === null) {
            return null;
        }

        return $this->mapper->mapRowToEntity($row);
    }

    private function getNextPosition(Setting $entity): int
    {
        $query = $this->connection
            ->select(['MAX(position) as position'])
            ->from('{{%setting}}')
            ->where(['group_uuid' => $entity->getGroupUuid()]);
        
        $max = (int) $query->scalar();

        return $max + 1;
    }

    public function findOneByUuid(string $uuid): ?Setting
    {
        $query = $this->connection
            ->select()
            ->from('{{%setting}}')
            ->where(['uuid' => $uuid]);

        return $this->createEntity($query->one());
    }

    public function findOneByKey(string $key): ?Setting
    {
        $query = $this->connection
            ->select()
            ->from('{{%setting}}')
            ->where(['key' => $key]);

        return $this->createEntity($query->one());
    }

    public function findAll(): array
    {
        $rows = $this->connection
            ->select()
            ->from('{{%setting}}')
            ->orderBy(['position' => SORT_ASC])
            ->all();

        $items = [];
        foreach ($rows as $row) {
            $items[] = $this->createEntity($row);
        }

        return $items;
    }
}
