<?php

declare(strict_types=1);

namespace Atom\Web\User\Trash;

use Atom\Entity\User;
use Yiisoft\Html\Html;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;

$title = 'Trash';

$this->setTitle($title);

?>
<h1><?= Html::encode($title) ?></h1>

<?php if ($dataReader->count() > 0): ?>
    <div class="mb-2">
        <?= Html::a(Html::i()->class('fa-solid fa-trash-can me-2')->render() . 'Empty Trash')
            ->url($urlGenerator->generate('atom.user.empty-trash'))
            ->class('btn btn-danger')
            ->addAttributes([
                'data-method' => 'POST',
                'data-confirm' => 'Are you sure you want to permanently delete all items in the trash? This action cannot be undone.',
            ])
            ->encode(false)
        ?>
    </div>
<?php endif; ?>

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
            property: 'role',
            header: 'Role',
            content: static function (User $user): string {
                $role = $user->getRole();

                $options = ['class' => 'badge'];
                Html::addCssClass($options, $role->getCssClass());

                return Html::span(
                    Html::encode($role->getLabel()),
                    $options,
                )->render();
            },
            encodeContent: false,
        ),
        new DataColumn(
            property: 'deletedAt',
            header: 'Deleted At',
            content: static fn (User $user): string => $user->getDeletedAt()->format('Y-m-d H:i:s'),
        ),
        new ActionColumn(
            buttons: [
                'restore' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-rotate-left']),
                    attributes: [
                        'title' => 'Restore',
                        'data-method' => 'POST',
                        'data-confirm' => 'Are you sure you want to restore this item?',
                    ],
                ),
            ],
            urlCreator: function ($action, $context) use ($urlGenerator) {
                return $urlGenerator->generate('atom.user.' . $action, ['uuid' => $context->data->getUuid()]);
            },
        ),
    )
?>
