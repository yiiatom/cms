<?php

declare(strict_types=1);

use Atom\Entity\User;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;

$title = 'Users';

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-user-filter row row-cols-sm-auto g-2 align-items-center mb-2')
    ->get();

?>

<h1><?= Html::encode($title) ?></h1>

<div class="mb-2"><?= Html::a('Create User')->url($urlGenerator->generate('atom.user.create'))->class('btn btn-primary') ?></div>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'search', theme: 'inline')
        ->placeholder($form->getPropertyLabel('search'))
        ->template('<div class="input-group"><div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>{input}</div>') ?>
    <?= Field::select($form, 'status', theme: 'inline')->optionsData($form->getStatusOptions()) ?>
    <?= Field::select($form, 'role', theme: 'inline')->optionsData($form->getRoleOptions()) ?>
    <div class="col-12">
        <?= Html::submitButton('Filter')->class('btn btn-primary') ?>
        <?= Html::a('Reset')->url($urlGenerator->generate('atom.user.index'))->class('btn btn-outline-secondary') ?>
    </div>
<?= $htmlForm->close() ?>

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
            property: 'role',
            header: 'Role',
            content: static function (User $user): string {
                $role = $user->getRole();

                $options = ['class' => 'badge'];
                Html::addCssClass($options, $role->getCssClass());

                return Html::tag(
                    'span',
                    Html::encode($role->getLabel()),
                    $options,
                )->render();
            },
            encodeContent: false,
        ),
        new DataColumn(
            property: 'createdAt',
            header: 'Created At',
            content: static fn (User $user): string => $user->getCreatedAt()->format('Y-m-d H:i:s'),
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
                return $urlGenerator->generate('atom.user.' . $action, ['uuid' => $context->data->getUuid()]);
            },
            visibleButtons: [
                'edit' => static fn (User $user): bool => !$user->isSuperAdmin(),
                'password' => static fn (User $user): bool => !$user->isSuperAdmin(),
                'delete' => static fn (User $user): bool => !$user->isSuperAdmin(),
            ],
        ),
    )
?>
