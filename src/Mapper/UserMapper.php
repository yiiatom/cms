<?php

declare(strict_types=1);

namespace Atom\Mapper;

use Atom\Entity\User;
use Atom\Entity\UserStatus;
use Closure;
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
            'status' => UserStatus::from((int) $row['status']),
            'isSuperAdmin' => (bool) $row['is_superadmin'],
            'firstName' => $row['first_name'],
            'lastName' => $row['last_name'],
            'avatarUrl' => $row['avatar_url'],
            'createdAt' => new DateTimeImmutable($row['created_at']),
            'updatedAt' => new DateTimeImmutable($row['updated_at']),
            'deletedAt' => $row['deleted_at'] ? new DateTimeImmutable($row['deleted_at']) : null,
        ];

        return $this->hydrator->create(User::class, $data);
    }

    public function mapEntityToRow(User $entity): array
    {
        $extractor = function (): array {
            return [
                'uuid' => $this->uuid,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'password_expires_at' => $this->passwordExpiresAt,
                'status' => $this->status->value,
                'is_superadmin' => $this->isSuperAdmin,
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'avatar_url' => $this->avatarUrl,
                'created_at' => $this->createdAt,
                'updated_at' => $this->updatedAt,
                'deleted_at' => $this->deletedAt,
            ];
        };

        $extractorClosure = Closure::bind($extractor, $entity, User::class);

        return $extractorClosure();
    }
}
