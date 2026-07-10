<?php

declare(strict_types=1);

namespace Atom\Identity;

use Atom\Data\UserAuthKeyRepository;
use Atom\Data\UserRepository;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class IdentityRepository implements IdentityRepositoryInterface
{
    public function __construct(
        private UserAuthKeyRepository $userAuthKeyRepository,
        private UserRepository $userRepository,
    ) {}

    public function findIdentity(string $id): ?IdentityInterface
    {
        $user = $this->userRepository->findOne($id);
        return $user ? new UserIdentity($user, $this->userAuthKeyRepository) : null;
    }
}

