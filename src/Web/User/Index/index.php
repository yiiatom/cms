<?php

declare(strict_types=1);

use Atom\Entity\User;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 * @var int $deletedCount
 * @var UserFilterForm $form
 * @var DataReaderInterface $dataReader
 */

$title = $t->translate('Users');

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-user-filter row row-cols-sm-auto g-2 align-items-center mb-2')
    ->get();

$trashLabel = Html::i()->class('fa-solid fa-trash-can me-2')->render() . Html::encode($t->translate('Trash'));
if ($deletedCount > 0) {
    $trashLabel .= Html::span($deletedCount, ['class' => 'badge bg-secondary ms-2'])->render();
}

?>
<h1><?= Html::encode($title) ?></h1>

<div class="mb-2 d-flex justify-content-between align-items-center">
    <?= Html::a($t->translate('Add User'))
        ->url($urlGenerator->generate('atom.user.create'))
        ->class('btn btn-primary me-2')
    ?>
    <?= Html::a($trashLabel)
        ->url($urlGenerator->generate('atom.user.trash'))
        ->class('btn btn-outline-secondary')
        ->encode(false)
    ?>
</div>

<div class="border-top pt-2">
    <?= $htmlForm->open() ?>
        <?= Field::text($form, 'search', theme: 'inline')
            ->placeholder($form->getPropertyLabel('search'))
            ->template('<div class="input-group"><div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>{input}</div>')
        ?>
        <?= Field::select($form, 'status', theme: 'inline')->optionsData($form->getStatusOptions()) ?>
        <?= Field::select($form, 'role', theme: 'inline')->optionsData($form->getRoleOptions()) ?>
        <div>
            <?= Html::submitButton($t->translate('Apply'))
                ->class('btn btn-primary me-1')
            ?>
            <?= Html::a($t->translate('Clear'))
                ->url($urlGenerator->generate('atom.user.index'))
                ->class('btn btn-outline-secondary')
            ?>
        </div>
    <?= $htmlForm->close() ?>
</div>

<?= GridView::widget()
    ->dataReader($dataReader)
    ->columns(
        new DataColumn(
            property: 'username',
            header: $t->translate('Username'),
            content: static fn (User $user): string => $user->getUsername(),
        ),
        new DataColumn(
            property: 'email',
            header: $t->translate('Email'),
            content: static fn (User $user): string => $user->getEmail() ?? '',
        ),
        new DataColumn(
            property: 'status',
            header: $t->translate('Status'),
            content: static function (User $user) use ($t): string {
                $status = $user->getStatus();

                $options = ['class' => 'badge'];
                Html::addCssClass($options, $status->getCssClass());

                return Html::span(
                    Html::encode($t->translate($status->getLabel())),
                    $options,
                )->render();
            },
            encodeContent: false,
        ),
        new DataColumn(
            property: 'role',
            header: $t->translate('Role'),
            content: static function (User $user) use ($t): string {
                $role = $user->getRole();

                $options = ['class' => 'badge'];
                Html::addCssClass($options, $role->getCssClass());

                return Html::span(
                    Html::encode($t->translate($role->getLabel())),
                    $options,
                )->render();
            },
            encodeContent: false,
        ),
        new DataColumn(
            property: 'createdAt',
            header: $t->translate('Created At'),
            content: static fn (User $user): string => $user->getCreatedAt()->format('Y-m-d H:i:s'),
        ),
        new ActionColumn(
            buttons: [
                'edit' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-pencil']),
                    attributes: ['title' => $t->translate('Edit')],
                ),
                'password' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-key']),
                    attributes: ['title' => $t->translate('Change Password')],
                ),
                'delete' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-trash']),
                    attributes: [
                        'title' => $t->translate('Delete'),
                        'data-method' => 'POST',
                        'data-confirm' => $t->translate('Are you sure you want to delete this user?'),
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
