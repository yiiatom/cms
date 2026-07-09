<?php

declare(strict_types=1);

use Atom\User\Entity\User;
use Ramsey\Uuid\Uuid;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;
use Yiisoft\Security\PasswordHasher;

final class M260709035701User implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    private const USER_TABLE = '{{%user}}';

    public function up(MigrationBuilder $b): void
    {
        $this->createUserTable($b);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable(self::USER_TABLE);
    }

    private function createUserTable(MigrationBuilder $b): void
    {
        $b->createTable(self::USER_TABLE, [
            'uuid' => ColumnBuilder::string(36)->notNull(),
            'username' => ColumnBuilder::string(20)->notNull(),
            'email' => ColumnBuilder::string(254),
            'password' => ColumnBuilder::string(64),
            'token' => ColumnBuilder::string(64),
            'auth_key' => ColumnBuilder::string(36),
            'status' => ColumnBuilder::integer()->notNull(),
            'first_name' => ColumnBuilder::string(100),
            'last_name' => ColumnBuilder::string(100),
            'avatar_url' => ColumnBuilder::string(255),
            'created_at' => ColumnBuilder::datetime(),
            'login_at' => ColumnBuilder::datetime(),
            'login_ip' => ColumnBuilder::string(15),
        ]);

        $b->addPrimaryKey(self::USER_TABLE, 'uuid', 'uuid');
        $b->createIndex(self::USER_TABLE, 'username', 'username', 'UNIQUE');
        $b->createIndex(self::USER_TABLE, 'token', 'token', 'UNIQUE');
        $b->createIndex(self::USER_TABLE, 'email', 'email', 'UNIQUE');
        $b->createIndex(self::USER_TABLE, 'auth_key', 'auth_key', 'UNIQUE');

        $b->insert(self::USER_TABLE, [
            'uuid' => Uuid::uuid7()->toString(),
            'username' => 'admin',
            'email' => null,
            'password' => (new PasswordHasher())->hash('admin'),
            'token' => null,
            'auth_key' => null,
            'status' => User::STATUS_ACTIVE,
            'first_name' => null,
            'last_name' => null,
            'avatar_url' => null,
            'created_at' => null,
            'login_at' => null,
            'login_ip' => null,
        ]);
    }
}
