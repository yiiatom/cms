<?php

declare(strict_types=1);

namespace Atom\Data;

use DateTimeImmutable;
use Atom\Data\UserDataReader;
use Atom\Entity\User;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

final readonly class UserRepository
{
    public function __construct(
        private ConnectionInterface $connection,
    ) { }

    public function exists(string $uuid): bool
    {
        return $this->connection->createQuery()
            ->from('{{%user}}')
            ->where(['uuid' => $uuid])
            ->exists();
    }

    public function save(User $entity): void
    {
        $row = [
            'uuid' => $entity->uuid,
            'username' => $entity->username,
            'email' => $entity->email,
            'password' => $entity->password,
            'password_expires_at' => $entity->passwordExpiresAt,
            'status' => $entity->status,
            'first_name' => $entity->firstName,
            'last_name' => $entity->lastName,
            'avatar_url' => $entity->avatarUrl,
            'updated_at' => new DateTimeImmutable(),
        ];

        if ($this->exists($entity->uuid)) {
            $this->connection->createCommand()->update('{{%user}}', $row, ['uuid' => $entity->uuid])->execute();
        } else {
            $row['created_at'] = $row['updated_at'];
            $this->connection->createCommand()->insert('{{%user}}', $row)->execute();
        }
    }

    private function createEntity(?array $row): ?User
    {
        if ($row === null) {
            return null;
        }

        return User::create(
            uuid: $row['uuid'],
            username: $row['username'],
            email: $row['email'],
            password: $row['password'],
            passwordExpiresAt: $row['password_expires_at'] ? new DateTimeImmutable($row['password_expires_at']) : null,
            status: (int) $row['status'],
            firstName: $row['first_name'],
            lastName: $row['last_name'],
            avatarUrl: $row['avatar_url'],
            createdAt: $row['created_at'] ? new DateTimeImmutable($row['created_at']) : null,
            updatedAt: $row['updated_at'] ? new DateTimeImmutable($row['updated_at']) : null,
            deletedAt: $row['deleted_at'] ? new DateTimeImmutable($row['deleted_at']) : null,
        );
    }

    public function findOne(string $uuid): ?User
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where('uuid = :uuid', ['uuid' => $uuid]);

        return $this->createEntity($query->one());
    }

    public function findOneByUsername(string $username): ?User
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where('username = :username', ['username' => $username]);

        return $this->createEntity($query->one());
    }


    public function findAllDataReader(): UserDataReader
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}');

        $reader = new QueryDataReader($query);

        return new UserDataReader($reader);
    }
}
