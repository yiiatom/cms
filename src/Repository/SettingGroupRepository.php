<?php

declare(strict_types=1);

namespace Atom\Repository;

use Atom\Entity\SettingGroup;
use Atom\Mapper\SettingGroupMapper;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class SettingGroupRepository
{
    public function __construct(
        private ConnectionInterface $connection,
        private SettingGroupMapper $mapper,
    ) {}

    public function exists(string $uuid): bool
    {
        return $this->connection->createQuery()
            ->from('{{%setting_group}}')
            ->where(['uuid' => $uuid])
            ->exists();
    }

    public function save(SettingGroup $entity): void
    {
        if ($this->exists($entity->getUuid())) {
            $this->update($entity);
        } else {
            $this->insert($entity);
        }
    }

    public function insert(SettingGroup $entity): void
    {
        $position = $this->getNextPosition();
        $entity->setPosition($position);

        $row = $this->mapper->mapEntityToRow($entity);
        $this->connection->createCommand()
            ->insert('{{%setting_group}}', $row)
            ->execute();
    }

    public function update(SettingGroup $entity): void
    {
        $row = $this->mapper->mapEntityToRow($entity);
        $this->connection->createCommand()
            ->update('{{%setting_group}}', $row, ['uuid' => $entity->getUuid()])
            ->execute();
    }

    public function delete(SettingGroup $entity): int
    {
        return $this->connection->createCommand()
            ->delete('{{%setting_group}}', ['uuid' => $entity->getUuid()])
            ->execute();
    }

    private function createEntity(?array $row): ?SettingGroup
    {
        if ($row === null) {
            return null;
        }

        return $this->mapper->mapRowToEntity($row);
    }

    private function getNextPosition(): int
    {
        $query = $this->connection
            ->select(['MAX(position) as position'])
            ->from('{{%setting_group}}');
        
        $max = (int) $query->scalar();

        return $max + 1;
    }

    public function findOneByUuid(string $uuid): ?SettingGroup
    {
        $query = $this->connection
            ->select()
            ->from('{{%setting_group}}')
            ->where('uuid = :uuid', ['uuid' => $uuid]);

        return $this->createEntity($query->one());
    }

    public function findOneByName(string $name): ?SettingGroup
    {
        $query = $this->connection
            ->select()
            ->from('{{%setting_group}}')
            ->where('name = :name', ['name' => $name]);

        return $this->createEntity($query->one());
    }

    public function findAll(): array
    {
        $rows = $this->connection
            ->select()
            ->from('{{%setting_group}}')
            ->orderBy(['position' => SORT_ASC])
            ->all();

        $items = [];
        foreach ($rows as $row) {
            $items[] = $this->createEntity($row);
        }

        return $items;
    }

    public function updatePositions(array $positions): void
    {
        $this->connection->transaction(function () use ($positions): void {
            foreach ($positions as $uuid => $position) {
                $this->connection->createCommand()
                    ->update('{{%setting_group}}', ['position' => $position], ['uuid' => $uuid])
                    ->execute();
            }
        });
    }
}
