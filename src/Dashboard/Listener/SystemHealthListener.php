<?php

declare(strict_types=1);

namespace Atom\Dashboard\Listener;

use Atom\Dashboard\DashboardCard;
use Atom\Dashboard\DashboardCardItem;
use Atom\Dashboard\Event\DashboardEvent;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Translator\TranslatorInterface;

final class SystemHealthListener
{
    public function __construct(
        private string $appEnv,
        private bool $appDebug,
        private Aliases $aliases,
        private TranslatorInterface $translator,
    ) {}

    public function __invoke(DashboardEvent $event): void
    {
        $t = $this->translator->withDefaultCategory('atom-dashboard');

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

        $card = new DashboardCard(
            title: $t->translate('System Health'),
            icon: 'fa-solid fa-heart-pulse',
            order: 10,
            items: [
                new DashboardCardItem(
                    $t->translate('PHP Version'),
                    PHP_VERSION,
                ),
                new DashboardCardItem(
                    $t->translate('Environment'),
                    $environmentName,
                    $this->appEnv === 'prod' ? 'default' : 'warning',
                ),
                new DashboardCardItem(
                    $t->translate('Debug'),
                    $this->appDebug ? $t->translate('Enabled') : $t->translate('Disabled'),
                    $this->appEnv === 'prod' && $this->appDebug ? 'danger' : 'default',
                ),
                new DashboardCardItem(
                    '/runtime',
                    $isRuntimeWritable ? $t->translate('Writable') : $t->translate('Not Writable'),
                    !$isRuntimeWritable ? 'danger' : 'default',
                ),
                new DashboardCardItem(
                    '/public/assets',
                    $isAssetsWritable ? $t->translate('Writable') : $t->translate('Not Writable'),
                    !$isAssetsWritable ? 'danger' : 'default',
                ),
                new DashboardCardItem(
                    $t->translate('Disk Space'),
                    $diskValue,
                    $diskStatus,
                ),
            ],
        );

        $event->addCard($card);
    }
}
