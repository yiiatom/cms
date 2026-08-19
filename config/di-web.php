<?php

declare(strict_types=1);

use Atom\Dashboard\Listener\SystemHealthListener;
use Atom\Identity\IdentityRepository;
use Atom\Security\PasswordHasherAdepter;
use Atom\Security\PasswordHasherInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\IdMessageReader;
use Yiisoft\Translator\IntlMessageFormatter;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\SimpleMessageFormatter;

return [
    IdentityRepositoryInterface::class => IdentityRepository::class,
    PasswordHasherInterface::class => PasswordHasherAdepter::class,

    SystemHealthListener::class => [
        '__construct()' => [
            'appEnv' => $params['atom.env'],
            'appDebug' => $params['atom.debug'],
        ]
    ],

    'atom.cms.categorySource' => [
        'definition' => static function () use ($params): CategorySource {
            $reader = class_exists(MessageSource::class)
                ? new MessageSource(\dirname(__DIR__) . '/messages')
                : new IdMessageReader(); // @codeCoverageIgnore

            $formatter = \extension_loaded('intl')
                ? new IntlMessageFormatter()
                : new SimpleMessageFormatter(); // @codeCoverageIgnore

            return new CategorySource('atom-cms', $reader, $formatter);
        },
        'tags' => ['translation.categorySource'],
    ],

    'atom.dashboard.categorySource' => [
        'definition' => static function () use ($params): CategorySource {
            $reader = class_exists(MessageSource::class)
                ? new MessageSource(\dirname(__DIR__) . '/messages')
                : new IdMessageReader(); // @codeCoverageIgnore

            $formatter = \extension_loaded('intl')
                ? new IntlMessageFormatter()
                : new SimpleMessageFormatter(); // @codeCoverageIgnore

            return new CategorySource('atom-dashboard', $reader, $formatter);
        },
        'tags' => ['translation.categorySource'],
    ],

    'atom.users.categorySource' => [
        'definition' => static function () use ($params): CategorySource {
            $reader = class_exists(MessageSource::class)
                ? new MessageSource(\dirname(__DIR__) . '/messages')
                : new IdMessageReader(); // @codeCoverageIgnore

            $formatter = \extension_loaded('intl')
                ? new IntlMessageFormatter()
                : new SimpleMessageFormatter(); // @codeCoverageIgnore

            return new CategorySource('atom-users', $reader, $formatter);
        },
        'tags' => ['translation.categorySource'],
    ],
];
