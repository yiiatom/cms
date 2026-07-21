<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Atom\Event\DashboardEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;

final class DashboardService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function getCardsDataReader(): IterableDataReader
    {
        $cards = [
            new DashboardCard('Title1', 'Value1', 'fa-user', 'bg-primary'),
            new DashboardCard('Title2', 'Value2', 'fa-user', 'bg-primary'),
        ];

        $event = $this->eventDispatcher->dispatch(new DashboardEvent($cards));

        return new IterableDataReader($event->getCards());
    }
}
