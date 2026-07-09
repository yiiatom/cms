# Atom CMS for Yii3 Framework

## Installation

To install the CMS, follow these steps:

### 1. Install the framework

For more information see https://yiisoft.github.io/docs/guide/start/creating-project.html

```
composer create-project yiisoft/app your_project
```

### 2. Configure your application

#### Database configuration

```php
// config/common/params.php

use Yiisoft\Db\Mysql\Dsn;

return [
    ...
    'yiisoft/db-mysql' => [
        'dsn' => new Dsn(
            'mysql',
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_PORT']
        ),
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
    ...
];
```

```php
// config/common/di/db-mysql.php

declare(strict_types=1);

use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Mysql\Connection;
use Yiisoft\Db\Mysql\Driver;

/** @var array $params */

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
```

```
// .env

...
DB_HOST=127.0.0.1
DB_NAME=your_database_name
DB_PORT=3306
DB_USERNAME=your_username
DB_PASSWORD=your_password
...
```

#### User configuration

```php
// config/web/di/user.php

declare(strict_types=1);

use Yiisoft\Definitions\Reference;
use Yiisoft\Session\SessionInterface;
use Yiisoft\User\CurrentUser;

return [
    CurrentUser::class => [
        'withSession()' => [Reference::to(SessionInterface::class)]
    ],
];
```

```php
// config/common/params.php

return [
    ...
    'yiisoft/session' => [
        'session' => [
            'options' => [
                'cookie_secure' => 0,
            ],
        ],
    ],
    ...
];
```

### 3. Apply database migrations


## Changing CMS base path
