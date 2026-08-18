<?php

declare(strict_types=1);

namespace Atom\Dashboard;

use Atom\Dashboard\Event\DashboardEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;

final class DashboardCardsProvider
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function getCardsAsDataReader(): IterableDataReader
    {
        $event = $this->eventDispatcher->dispatch(new DashboardEvent());

        return new IterableDataReader($event->getCards());
    }
}
