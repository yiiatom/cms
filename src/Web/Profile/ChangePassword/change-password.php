<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$title = $t->translate('Change Password');

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-constrained')
    ->post()
    ->csrf($csrf);
?>

<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::password($form, 'currentPassword') ?>
    <?= Field::password($form, 'newPassword') ?>
    <?= Field::password($form, 'confirmPassword') ?>
    <?= Html::submitButton($t->translate('Update Password'), ['class' => 'btn btn-primary']) ?>
<?= $htmlForm->close() ?>
