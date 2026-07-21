<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

final class DashboardEvent
{
    public function __construct(
        private array $cards,
    ) {}

    public function addCard(DashboardCard $card): self
    {
        $this->cards[] = $card;
        return $this;
    }

    public function getCards(): array
    {
        usort($this->cards, static fn(DashboardCard $a, DashboardCard $b) => $a->order <=> $b->order);
        return $this->cards;
    }
}
