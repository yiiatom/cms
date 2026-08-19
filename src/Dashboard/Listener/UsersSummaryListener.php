<?php

declare(strict_types=1);

namespace Atom\Dashboard\Listener;

use Atom\Dashboard\DashboardCard;
use Atom\Dashboard\DashboardCardItem;
use Atom\Dashboard\Event\DashboardEvent;
use Atom\Repository\UserRepository;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

final class UsersSummaryListener
{
    public function __construct(
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(DashboardEvent $event): void
    {
        $t = $this->translator->withDefaultCategory('atom-dashboard');

        $userStats = $this->userRepository->getSummaryStats();

        $card = new DashboardCard(
            title: $t->translate('Users'),
            icon: 'fa-solid fa-users',
            items: [
                new DashboardCardItem(
                    $t->translate('Total'),
                    (string) $userStats['total'],
                ),
                new DashboardCardItem(
                    $t->translate('Active'),
                    (string) $userStats['active'],
                ),
                new DashboardCardItem(
                    $t->translate('Blocked'),
                    (string) $userStats['blocked'],
                ),
                new DashboardCardItem(
                    $t->translate('New'),
                    (string) $userStats['new'],
                ),
            ],
            order: 20,
            linkUrl: $this->urlGenerator->generate('atom.user.index'),
            linkText: $t->translate('Manage Users'),
        );

        $event->addCard($card);
    }
}
