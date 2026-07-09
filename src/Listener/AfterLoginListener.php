<?php

declare(strict_types=1);

namespace Atom\Listener;

use DateTimeImmutable;
use Atom\Data\UserRepository;
use Yiisoft\User\Event\AfterLogin;

final readonly class AfterLoginListener
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    function __invoke(
        AfterLogin $event,
    ): void {
        $identity = $event->getIdentity();
        $identity->loginAt = new DateTimeImmutable();
        $identity->loginIp = $_SERVER['REMOTE_ADDR'] ?? null;
        $this->userRepository->save($identity);
    }
}
