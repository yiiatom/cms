<?php

declare(strict_types=1);

namespace Atom\Repository;

use DateTimeImmutable;
use Atom\Data\UserDataReader;
use Atom\Entity\User;
use Atom\Mapper\UserMapper;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

final readonly class UserRepository
{
    public function __construct(
        private ConnectionInterface $connection,
        private UserMapper $mapper,
    ) {}

    public function exists(string $uuid): bool
    {
        return $this->connection->createQuery()
            ->from('{{%user}}')
            ->where(['uuid' => $uuid])
            ->exists();
    }

    public function superAdminExist(): bool
    {
        return $this->connection->createQuery()
            ->from('{{%user}}')
            ->where(['is_superadmin' => true])
            ->exists();
    }

    public function save(User $entity): void
    {
        $row = $this->mapper->mapEntityToRow($entity);
        $uuid = $entity->getUuid();

        if ($this->exists($uuid)) {
            $this->connection->createCommand()->update('{{%user}}', $row, ['uuid' => $uuid])->execute();
        } else {
            $this->connection->createCommand()->insert('{{%user}}', $row)->execute();
        }
    }

    private function createEntity(?array $row): ?User
    {
        if ($row === null) {
            return null;
        }

        return $this->mapper->mapRowToEntity($row);
    }

    public function findOneByUuid(string $uuid): ?User
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

    public function findOneByEmail(string $email): ?User
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where('email = :email', ['email' => $email]);

        return $this->createEntity($query->one());
    }


    public function findAllAsDataReader(array $filters = []): UserDataReader
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}');

        $search = $filters['search'] ?? null;
        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['like', 'username', $search],
                ['like', 'email', $search],
                ['like', 'first_name', $search],
                ['like', 'last_name', $search],
            ]);
        }

        $status = $filters['status'] ?? null;
        if ($status !== null && $status !== '') {
            $query->andWhere(['status' => (int) $status]);
        }

        $role = $filters['role'] ?? null;
        if ($role !== null && $role !== '') {
            $query->andWhere(['role' => (int) $role]);
        }

        $reader = new QueryDataReader($query);

        return new UserDataReader($reader, $this->mapper);
    }
}
