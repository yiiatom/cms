<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Atom\Event\DashboardEvent;
use Atom\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;

final class DashboardService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private UserRepository $userRepository,
    ) {}

    public function getCardsDataReader(): IterableDataReader
    {
        $totalUsers = $this->userRepository->count();

        $cards = [
            new DashboardCard(
                title: 'Total Users',
                value: (string) $totalUsers,
                icon: 'fa-solid fa-users',
                bgClass: 'bg-primary',
                order: 10,
            ),
        ];

        $event = $this->eventDispatcher->dispatch(new DashboardEvent($cards));

        return new IterableDataReader($event->getCards());
    }
}
