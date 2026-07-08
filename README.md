=== Database configuration
// config/common/params.php
'yiisoft/db-mysql' => [
    'dsn' => new Dsn('mysql', '127.0.0.1', 'atom', '3306'),
    'username' => 'atom',
    'password' => 'atom',
],

// config/common/di/db-mysql.php
return [
    ConnectionInterface::class => [
        'class' => Connection::class,
        '__construct()' => [
            'driver' => new Driver(
                $params['yiisoft/db-mysql']['dsn'],
                $params['yiisoft/db-mysql']['username'],
                $params['yiisoft/db-mysql']['password'],
            ),
        ],
    ],
];


=== User configuration
// config/web/di/user.php
return [
    CurrentUser::class => [
        'withSession()' => [Reference::to(SessionInterface::class)]
    ],
];


=== Using non-secure requests (http instead of https)
// common/params.php
'yiisoft/session' => [
    'session' => [
        'options' => [
            'cookie_secure' => 0,
        ],
    ],
],



Migrations

Changing CMS base path
