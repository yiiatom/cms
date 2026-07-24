<?php

declare(strict_types=1);

use Atom\Console\InitCommand;
use Atom\Injection\LayoutInjection;
use Yiisoft\Definitions\Reference;

return [
    'atom.env' => $_ENV['APP_ENV'] ?? 'prod',
    'atom.debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

    'yiisoft/aliases' => [
        'aliases' => [
            '@atom' => dirname(__DIR__),
        ],
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'cms:init' => InitCommand::class,
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
