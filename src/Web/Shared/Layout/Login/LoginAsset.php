<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Layout\Login;

use Yiisoft\Assets\AssetBundle;

final class LoginAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@atom/assets';

    public array $css = [
        'bootstrap/css/bootstrap.min.css',
        'fontawesome/css/all.min.css',
        'login.css',
    ];
}
