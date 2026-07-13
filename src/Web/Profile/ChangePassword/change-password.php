<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$title = 'Change Password';

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-default form-change-password')
    ->post()
    ->csrf($csrf);
?>

<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::password($form, 'oldPassword') ?>
    <?= Field::password($form, 'newPassword') ?>
    <?= Field::password($form, 'confirmPassword') ?>
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
<?= $htmlForm->close() ?>
