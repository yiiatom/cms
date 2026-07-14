<?php

declare(strict_types=1);

namespace Atom\Entity;

use DateTimeImmutable;
use Atom\Security\PasswordHasherInterface;
use Ramsey\Uuid\Uuid;

final class User
{
    public function __construct(
        private string $uuid,
        private string $username,
        private ?string $email,
        private ?string $password,
        private ?DateTimeImmutable $passwordExpiresAt,
        private bool $isSuperAdmin,
        private UserStatus $status,
        private UserRole $role,
        private ?string $firstName,
        private ?string $lastName,
        private ?string $avatarUrl,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
        private ?DateTimeImmutable $deletedAt,
    ) {}

    public static function create(
        string $username,
        ?string $uuid = null,
        ?string $email = null,
        ?string $password = null,
        ?DateTimeImmutable $passwordExpiresAt = null,
        bool $isSuperAdmin = false,
        UserStatus $status = UserStatus::PENDING,
        UserRole $role = UserRole::USER,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $avatarUrl = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $deletedAt = null,
    ): self {
        $date = new DateTimeImmutable;
        return new self(
            uuid: $uuid ?: Uuid::uuid7()->toString(),
            username: $username,
            email: $email,
            password: $password,
            passwordExpiresAt: $passwordExpiresAt,
            isSuperAdmin: $isSuperAdmin,
            status: $status,
            role: $role,
            firstName: $firstName,
            lastName: $lastName,
            avatarUrl: $avatarUrl,
            createdAt: $createdAt ?? $date,
            updatedAt: $updatedAt ?? $date,
            deletedAt: $deletedAt,
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $value): self
    {
        $this->email = $value;
        $this->updatedAt = new DateTimeImmutable;

        return $this;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setStatus(UserStatus $value): self
    {
        $this->status = $value;
        $this->updatedAt = new DateTimeImmutable;
        if ($value !== UserStatus::DELETED) {
            $this->deletedAt = null;
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $value): self
    {
        $this->firstName = $value;
        $this->updatedAt = new DateTimeImmutable;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $value): self
    {
        $this->lastName = $value;
        $this->updatedAt = new DateTimeImmutable;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function isSuperAdmin(): bool
    {
        return $this->isSuperAdmin;
    }

    public function validatePassword(string $password, PasswordHasherInterface $passwordHasher): bool
    {
        return $passwordHasher->validate($password, $this->password);
    }

    public function changePassword(string $password, PasswordHasherInterface $passwordHasher): void
    {
        $this->password = $passwordHasher->hash($password);
        $this->passwordExpiresAt = null;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function forcePasswordChange(): void
    {
        $this->passwordExpiresAt = new DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function isPasswordExpired(): bool
    {
        return $this->passwordExpiresAt && $this->passwordExpiresAt < new DateTimeImmutable();
    }

    public function delete(): void
    {
        $this->status = UserStatus::DELETED;
        $this->deletedAt = new DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable;
    }
}
