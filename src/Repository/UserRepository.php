<?php

declare(strict_types=1);

namespace Atom\Repository;

use DateTimeImmutable;
use Atom\Data\UserDataReader;
use Atom\Entity\User;
use Atom\Entity\UserStatus;
use Atom\Mapper\UserMapper;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Data\Reader\DataReaderInterface;
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

    public function purgeDeleted(): int
    {
        return $this->connection->createCommand()
            ->delete('{{%user}}', ['not', ['deleted_at' => null]])
            ->execute();
    }

    private function createEntity(?array $row): ?User
    {
        if ($row === null) {
            return null;
        }

        return $this->mapper->mapRowToEntity($row);
    }

    public function getSummaryStats(?DateTimeImmutable $newUsersDate = null): array
    {
        $stats = [
            'total' => 0,
            'active' => 0,
            'blocked' => 0,
            'new' => 0,
        ];

        $rows = $this->connection
            ->select(['status', 'COUNT(*) as count'])
            ->from('{{%user}}')
            ->groupBy('status')
            ->where(['deleted_at' => null])
            ->all();

        foreach ($rows as $row) {
            $count = (int) $row['count'];
            $stats['total'] += $count;

            if ($row['status'] == UserStatus::ACTIVE->value) {
                $stats['active'] += $count;
            } elseif ($row['status'] == UserStatus::BLOCKED->value) {
                $stats['blocked'] += $count;
            }
        }

        if ($newUsersDate === null) {
            $newUsersDate = (new DateTimeImmutable())->modify('-7 days');
        }

        $row = $this->connection
            ->select(['COUNT(*) as count'])
            ->from('{{%user}}')
            ->where(['deleted_at' => null])
            ->andWhere(['>', 'created_at', $newUsersDate->format('Y-m-d H:i:s')])
            ->one();

        if ($row) {
            $stats['new'] = (int) $row['count'];
        }

        return $stats;
    }

    public function getDeletedCount(): int
    {
        return $this->connection->createQuery()
            ->from('{{%user}}')
            ->where(['not', ['deleted_at' => null]])
            ->count();
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


    public function findAllAsDataReader(array $filters = []): DataReaderInterface
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where(['deleted_at' => null]);

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
            $query->andWhere(['status' => $status]);
        }

        $role = $filters['role'] ?? null;
        if ($role !== null && $role !== '') {
            $query->andWhere(['role' => $role]);
        }

        $reader = new QueryDataReader($query);

        return new UserDataReader($reader, $this->mapper);
    }

    public function findAllDeletedAsDataReader(): DataReaderInterface
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where(['not', ['deleted_at' => null]])
            ->orderBy([
                'deleted_at' => SORT_DESC,
                'username' => SORT_ASC,
            ]);

        $reader = new QueryDataReader($query);

        return new UserDataReader($reader, $this->mapper);
    }
}
