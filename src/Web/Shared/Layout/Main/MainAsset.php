<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Layout\Main;

use Yiisoft\Assets\AssetBundle;

final class MainAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@atom/assets';

    public array $css = [
        'bootstrap/css/bootstrap.min.css',
        'fontawesome/css/all.min.css',
        'main.css',
    ];

    public array $js = [
        'bootstrap/js/bootstrap.bundle.min.js',
    ];
}
