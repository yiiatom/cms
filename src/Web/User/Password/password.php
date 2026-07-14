<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$title = 'Change User Password';

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-default form-user-create')
    ->post()
    ->csrf($csrf);

?>
<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::password($form, 'newPassword') ?>
    <?= Field::password($form, 'confirmPassword') ?>
    <?= Field::checkbox($form, 'requirePasswordChange') ?>
    <?= Html::submitButton('Submit')->class('btn btn-primary') ?>
    <?= Html::a('Cancel')->url($urlGenerator->generate('atom.user.index'))->class('btn btn-outline-primary') ?>
<?= $htmlForm->close() ?>
