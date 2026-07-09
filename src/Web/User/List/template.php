<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;

$title = 'Users';

$this->setTitle($title);

?>

<h1><?= Html::encode($title) ?></h1>

<div class="mb-2"><?= Html::a('Create User')->url($urlGenerator->generate('atom.user.create'))->class('btn btn-primary') ?></div>

<?= GridView::widget()
    ->dataReader($dataReader)
    ->columns(
        new DataColumn(property: 'username'),
        new ActionColumn(
            buttons: [
                'edit' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-pencil']),
                    attributes: ['title' => 'Edit'],
                ),
                'delete' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-trash']),
                    attributes: ['title' => 'Delete'],
                ),
            ],
            urlCreator: function ($action, $context) use ($urlGenerator) {
                return $urlGenerator->generate('atom.user.' . $action, ['uuid' => $context->data->uuid]);
            }
        ),
    )
?>
