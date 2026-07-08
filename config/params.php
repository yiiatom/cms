<?php

declare(strict_types=1);

use Atom\User\Injection\LayoutInjection;
use Yiisoft\Definitions\Reference;

return [
    'yiisoft/aliases' => [
        'aliases' => [
            '@atom' => dirname(__DIR__),
        ],
    ],

    'yiisoft/yii-view-renderer' => [
        'injections' => [
            Reference::to(LayoutInjection::class),
        ],
    ],

    'yiisoft/db-migration' => [
        'sourcePaths' => [
            dirname(__DIR__) . '/migrations',
        ]
    ],
];
