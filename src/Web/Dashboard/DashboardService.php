<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Atom\Event\DashboardEvent;
use Atom\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Router\UrlGeneratorInterface;

final class DashboardService
{
    public function __construct(
        private string $appEnv,
        private bool $appDebug,
        private Aliases $aliases,
        private EventDispatcherInterface $eventDispatcher,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function getCardsDataReader(): IterableDataReader
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
                title: 'System Health',
                icon: 'fa-solid fa-heart-pulse',
                order: 10,
                items: [
                    new DashboardCardItem('PHP Version', PHP_VERSION),
                    new DashboardCardItem(
                        'Environment',
                        $environmentName,
                        $this->appEnv === 'prod' ? 'default' : 'warning',
                    ),
                    new DashboardCardItem(
                        'Debug',
                        $this->appDebug ? 'Enabled' : 'Disabled',
                        $this->appEnv === 'prod' && $this->appDebug ? 'danger' : 'default',
                    ),
                    new DashboardCardItem(
                        '/runtime',
                        $isRuntimeWritable ? 'Writable' : 'Not Writable',
                        !$isRuntimeWritable ? 'danger' : 'default',
                    ),
                    new DashboardCardItem(
                        '/public/assets',
                        $isAssetsWritable ? 'Writable' : 'Not Writable',
                        !$isAssetsWritable ? 'danger' : 'default',
                    ),
                    new DashboardCardItem('Disk Space', $diskValue, $diskStatus),
                ],
            ),
            new DashboardCard(
                title: 'Users',
                icon: 'fa-solid fa-users',
                items: [
                    new DashboardCardItem('Total', (string) $userStats['total']),
                    new DashboardCardItem('Active', (string) $userStats['active']),
                    new DashboardCardItem('Blocked', (string) $userStats['blocked']),
                    new DashboardCardItem('New', (string) $userStats['new']),
                ],
                order: 20,
                linkUrl: $this->urlGenerator->generate('atom.user.index'),
                linkText: 'Manage Users',
            ),
        ];

        $event = $this->eventDispatcher->dispatch(new DashboardEvent($cards));

        return new IterableDataReader($event->getCards());
    }
}
