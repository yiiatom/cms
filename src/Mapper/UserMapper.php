<?php

declare(strict_types=1);

namespace Atom\Mapper;

use Atom\Entity\User;
use DateTimeImmutable;
use Yiisoft\Hydrator\HydratorInterface;

final class UserMapper
{
    public function __construct(
        private HydratorInterface $hydrator,
    ) {}

    public function mapRowToEntity(array $row): User
    {
        $data = [
            'uuid' => $row['uuid'],
            'username' => $row['username'],
            'email' => $row['email'],
            'password' => $row['password'],
            'passwordExpiresAt' => $row['password_expires_at'] ? new DateTimeImmutable($row['password_expires_at']) : null,
            'status' => (int) $row['status'],
            'firstName' => $row['first_name'],
            'lastName' => $row['last_name'],
            'avatarUrl' => $row['avatar_url'],
            'createdAt' => new DateTimeImmutable($row['created_at']),
            'updatedAt' => new DateTimeImmutable($row['updated_at']),
            'deletedAt' => $row['deleted_at'] ? new DateTimeImmutable($row['deleted_at']) : null,
        ];

        return $this->hydrator->create(User::class, $data);
    }

    public function mapEntityToRow(User $user): array
    {
        $data = $this->hydrator->extract($user);

        return [
            'uuid' => $data['uuid'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'password_expires_at' => $data['passwordExpiresAt'],
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'avatar_url' => $data['avatarUrl'],
            'created_at' => $data['createdAt'],
            'updated_at' => $data['updatedAt'],
            'deleted_at' => $data['deletedAt'],
        ];
    }
}
