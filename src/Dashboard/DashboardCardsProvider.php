<?php

declare(strict_types=1);

namespace Atom\Dashboard;

use Atom\Dashboard\DashboardEvent;
use Atom\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

final class DashboardCardsProvider
{
    public function __construct(
        private string $appEnv,
        private bool $appDebug,
        private Aliases $aliases,
        private EventDispatcherInterface $eventDispatcher,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function getCardsAsDataReader(): IterableDataReader
    {
        $root = $this->aliases->get('@root');

        $environmentName = match ($this->appEnv) {
            'dev' => 'Development',
            'prod' => 'Production',
            'stage' => 'Staging',
            'test' => 'Testing',
            default => ucfirst($this->appEnv),
        };

        $isRuntimeWritable = file_exists($root . '/runtime') && is_writable($root . '/runtime');
        $isAssetsWritable = file_exists($root . '/public/assets') && is_writable($root . '/public/assets');

        $freeSpace = disk_free_space($root);
        if ($freeSpace >= 1024 ** 3) {
            $diskValue = round($freeSpace / 1024 ** 3, 1) . ' GB';
        } else {
            $diskValue = round($freeSpace / 1024 ** 2, 1) . ' MB';
        }

        $diskStatus = 'default';
        if ($freeSpace < 2 * 1024 ** 3) {
            $diskStatus = 'danger';
        } elseif ($freeSpace < 10 * 1024 ** 3) {
            $diskStatus = 'warning';
        }

        $userStats = $this->userRepository->getSummaryStats();

        $cards = [
            new DashboardCard(
                title: $this->translator->translate('System Health', [], 'atom-cms'),
                icon: 'fa-solid fa-heart-pulse',
                order: 10,
                items: [
                    new DashboardCardItem(
                        $this->translator->translate('PHP Version', [], 'atom-cms'),
                        PHP_VERSION,
                    ),
                    new DashboardCardItem(
                        $this->translator->translate('Environment', [], 'atom-cms'),
                        $environmentName,
                        $this->appEnv === 'prod' ? 'default' : 'warning',
                    ),
                    new DashboardCardItem(
                        $this->translator->translate('Debug', [], 'atom-cms'),
                        $this->appDebug ? $this->translator->translate('Enabled', [], 'atom-cms') : $this->translator->translate('Disabled', [], 'atom-cms'),
                        $this->appEnv === 'prod' && $this->appDebug ? 'danger' : 'default',
                    ),
                    new DashboardCardItem(
                        '/runtime',
                        $isRuntimeWritable ? $this->translator->translate('Writable', [], 'atom-cms') : $this->translator->translate('Not Writable', [], 'atom-cms'),
                        !$isRuntimeWritable ? 'danger' : 'default',
                    ),
                    new DashboardCardItem(
                        '/public/assets',
                        $isAssetsWritable ? $this->translator->translate('Writable', [], 'atom-cms') : $this->translator->translate('Not Writable', [], 'atom-cms'),
                        !$isAssetsWritable ? 'danger' : 'default',
                    ),
                    new DashboardCardItem(
                        $this->translator->translate('Disk Space', [], 'atom-cms'),
                        $diskValue,
                        $diskStatus,
                    ),
                ],
            ),
            new DashboardCard(
                title: $this->translator->translate('Users', [], 'atom-users'),
                icon: 'fa-solid fa-users',
                items: [
                    new DashboardCardItem(
                        $this->translator->translate('Total', [], 'atom-users'),
                        (string) $userStats['total'],
                    ),
                    new DashboardCardItem(
                        $this->translator->translate('Active', [], 'atom-users'),
                        (string) $userStats['active'],
                    ),
                    new DashboardCardItem(
                        $this->translator->translate('Blocked', [], 'atom-users'),
                        (string) $userStats['blocked'],
                    ),
                    new DashboardCardItem(
                        $this->translator->translate('New', [], 'atom-users'),
                        (string) $userStats['new'],
                    ),
                ],
                order: 20,
                linkUrl: $this->urlGenerator->generate('atom.user.index'),
                linkText: $this->translator->translate('Manage Users', [], 'atom-users'),
            ),
        ];

        $event = $this->eventDispatcher->dispatch(new DashboardEvent($cards));

        return new IterableDataReader($event->getCards());
    }
}
