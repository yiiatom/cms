<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Atom\Event\DashboardEvent;
use Atom\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Router\UrlGeneratorInterface;

final class DashboardService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function getCardsDataReader(): IterableDataReader
    {
        $stats = $this->userRepository->getSummaryStats();

        $cards = [
            new DashboardCard(
                title: 'Users',
                icon: 'fa-solid fa-users',
                items: [
                    'Total' => $stats['total'],
                    'Active' => $stats['active'],
                    'Blocked' => $stats['blocked'],
                    'New' => $stats['new'],
                ],
                order: 10,
                linkUrl: $this->urlGenerator->generate('atom.user.index'),
                linkText: 'Manage Users',
            ),
        ];

        $event = $this->eventDispatcher->dispatch(new DashboardEvent($cards));

        return new IterableDataReader($event->getCards());
    }
}
