<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$title = 'Change User Password';

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-default form-user-password')
    ->post()
    ->csrf($csrf);

?>
<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'username')
        ->readonly()
        ->disabled() ?>
    <?= Field::password($form, 'newPassword')->autofocus() ?>
    <?= Field::password($form, 'confirmPassword') ?>
    <?= Field::checkbox($form, 'requirePasswordChange') ?>
    <?= Html::submitButton('Submit')->class('btn btn-primary') ?>
    <?= Html::a('Cancel')->url($urlGenerator->generate('atom.user.index'))->class('btn btn-outline-secondary') ?>
<?= $htmlForm->close() ?>
