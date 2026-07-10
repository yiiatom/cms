<?php

declare(strict_types=1);

namespace Atom\Identity;

use Atom\Data\UserAuthKeyRepository;
use Atom\Entity\User;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\User\Login\Cookie\CookieLoginIdentityInterface;

final class UserIdentity implements IdentityInterface, CookieLoginIdentityInterface
{
    public function __construct(
        private User $user,
        private UserAuthKeyRepository $userAuthKeyRepository,
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getId(): string
    {
        return $this->user->uuid;
    }

    public function getCookieLoginKey(): string
    {
        $authKey = $this->userAuthKeyRepository->findLatestByUserUuid($this->user->uuid);
        return $authKey->value ?? '';
    }

    public function validateCookieLoginKey(string $key): bool
    {
        $authKey = $this->userAuthKeyRepository->findOne($key);
        return $authKey && $authKey->isValid($this->user->uuid);
    }
}
