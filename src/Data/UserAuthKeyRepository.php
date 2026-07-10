<?php

declare(strict_types=1);

namespace Atom\Data;

use DateTimeImmutable;
use Atom\Entity\UserAuthKey;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class UserAuthKeyRepository
{
    public function __construct(
        private ConnectionInterface $connection,
    ) { }

    public function exists(string $uuid): bool
    {
        return $this->connection->createQuery()
            ->from('{{%user_authkey}}')
            ->where(['uuid' => $uuid])
            ->exists();
    }

    public function save(UserAuthKey $entity): void
    {
        $row = [
            'uuid' => $entity->uuid,
            'user_uuid' => $entity->userUuid,
            'expires_at' => $entity->expiresAt,
        ];

        if ($this->exists($entity->uuid)) {
            $this->connection->createCommand()->update('{{%user_authkey}}', $row, ['uuid' => $entity->uuid])->execute();
        } else {
            $this->connection->createCommand()->insert('{{%user_authkey}}', $row)->execute();
        }
    }

    private function createEntity(?array $row): ?UserAuthKey
    {
        if ($row === null) {
            return null;
        }

        return UserAuthKey::create(
            uuid: $row['uuid'],
            userUuid: $row['user_uuid'],
            expiresAt: new DateTimeImmutable($row['expires_at']),
        );
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
