<?php

declare(strict_types=1);

namespace Atom\Data;

use DateTimeImmutable;
use Atom\Data\UserDataReader;
use Atom\Entity\User;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

final readonly class UserRepository implements IdentityRepositoryInterface
{
    public function __construct(
        private ConnectionInterface $connection,
    ) { }

    public function findIdentity(string $id): ?IdentityInterface
    {
        return $this->findOne($id);
    }

    public function findIdentityByToken(string $token, string $type): ?IdentityInterface
    {
        return $this->findOneByToken($token);
    }

    public function exists(string $uuid): bool
    {
        return $this->connection->createQuery()
            ->from('{{%user}}')
            ->where(['uuid' => $uuid])
            ->exists();
    }

    public function save(User $user): void
    {
        $row = [
            'uuid' => $user->uuid,
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->password,
            'password_expires_at' => $user->passwordExpiresAt,
            'token' => $user->token,
            'auth_key' => $user->authKey,
            'status' => $user->status,
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
            'avatar_url' => $user->avatarUrl,
            'created_at' => $user->createdAt,
            'login_at' => $user->loginAt,
            'login_ip' => $user->loginIp,
        ];

        if ($this->exists($user->uuid)) {
            $this->connection->createCommand()->update('{{%user}}', $row, ['uuid' => $user->uuid])->execute();
        } else {
            $this->connection->createCommand()->insert('{{%user}}', $row)->execute();
        }
    }

    public static function createEntity(?array $row): ?User
    {
        if ($row === null) {
            return null;
        }

        return new User(
            uuid: $row['uuid'],
            username: $row['username'],
            email: $row['email'],
            password: $row['password'],
            passwordExpiresAt: $row['password_expires_at'] ? new DateTimeImmutable($row['password_expires_at']) : null,
            token: $row['token'],
            authKey: $row['auth_key'],
            status: (int) $row['status'],
            firstName: $row['first_name'],
            lastName: $row['last_name'],
            avatarUrl: $row['avatar_url'],
            createdAt: $row['created_at'] ? new DateTimeImmutable($row['created_at']) : null,
            loginAt: $row['login_at'] ? new DateTimeImmutable($row['login_at']) : null,
            loginIp: $row['login_ip'],
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

    public function findOneByToken(string $token): ?User
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where('token = :token', ['token' => $token]);

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
