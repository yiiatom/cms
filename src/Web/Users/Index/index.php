<?php

declare(strict_types=1);

use Atom\Entity\User;
use Yiisoft\Html\Html;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;

$title = 'Users';

$this->setTitle($title);

?>

<h1><?= Html::encode($title) ?></h1>

<div class="mb-2"><?= Html::a('Create User')->url($urlGenerator->generate('atom.users.create'))->class('btn btn-primary') ?></div>

<?= GridView::widget()
    ->dataReader($dataReader)
    ->columns(
        new DataColumn(
            property: 'username',
            header: 'Username',
            content: static fn (User $user): string => $user->getUsername(),
        ),
        new DataColumn(
            property: 'email',
            header: 'Email',
            content: static fn (User $user): string => $user->getEmail() ?? '',
        ),
        new DataColumn(
            property: 'status',
            header: 'Status',
            content: static function (User $user): string {
                $status = $user->getStatus();

                $options = ['class' => 'badge'];
                Html::addCssClass($options, $status->getCssClass());

                return Html::tag(
                    'span',
                    Html::encode($status->getLabel()),
                    $options,
                )->render();
            },
            encodeContent: false,
        ),
        new DataColumn(
            property: 'createdAt',
            header: 'Created At',
            content: static fn (User $user): string => $user->getCreatedAt()->format('Y-m-d H:i'),
        ),
        new ActionColumn(
            buttons: [
                'edit' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-pencil']),
                    attributes: ['title' => 'Edit'],
                ),
                'password' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-key']),
                    attributes: ['title' => 'Change Password'],
                ),
                'delete' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-trash']),
                    attributes: [
                        'title' => 'Delete',
                        'data-confirm' => 'Are you sure you want to delete this item?',
                    ],
                ),
            ],
            urlCreator: function ($action, $context) use ($urlGenerator) {
                return $urlGenerator->generate('atom.users.' . $action, ['uuid' => $context->data->getUuid()]);
            },
            visibleButtons: [
                'edit' => static fn (User $user): bool => !$user->isSuperAdmin(),
                'password' => static fn (User $user): bool => !$user->isSuperAdmin(),
                'delete' => static fn (User $user): bool => !$user->isSuperAdmin(),
            ],
        ),
    )
?>
