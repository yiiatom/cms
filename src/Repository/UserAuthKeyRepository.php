<?php

declare(strict_types=1);

namespace Atom\Repository;

use DateTimeImmutable;
use Atom\Entity\UserAuthKey;
use Atom\Mapper\UserAuthKeyMapper;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class UserAuthKeyRepository
{
    public function __construct(
        private ConnectionInterface $connection,
        private UserAuthKeyMapper $mapper,
    ) {}

    public function exists(string $uuid): bool
    {
        return $this->connection->createQuery()
            ->from('{{%user_authkey}}')
            ->where(['uuid' => $uuid])
            ->exists();
    }

    public function save(UserAuthKey $entity): void
    {
        $row = $this->mapper->mapEntityToRow($entity);
        $uuid = $entity->getUuid();

        if ($this->exists($uuid)) {
            $this->connection->createCommand()->update('{{%user_authkey}}', $row, ['uuid' => $uuid])->execute();
        } else {
            $this->connection->createCommand()->insert('{{%user_authkey}}', $row)->execute();
        }
    }

    private function createEntity(?array $row): ?UserAuthKey
    {
        if ($row === null) {
            return null;
        }

        return $this->mapper->mapRowToEntity($row);
    }

    public function findLatestByUserUuid(string $userUuid): ?UserAuthKey
    {
        $query = $this->connection->createQuery()
            ->from('{{%user_authkey}}')
            ->where(['user_uuid' => $userUuid])
            ->orderBy(['uuid' => SORT_DESC])
            ->limit(1);

        return $this->createEntity($query->one());
    }
}
