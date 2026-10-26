<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Index;

use Yiisoft\Assets\AssetBundle;

final class SettingsManageAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@atom/assets';

    public array $css = [
        'settings.css',
    ];

    public array $js = [
        'sortable/sortable.min.js',
        'settings.js',
    ];
}
