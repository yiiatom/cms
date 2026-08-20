<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$title = $t->translate('Profile');

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-constrained')
    ->post()
    ->csrf($csrf);
?>

<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'username')
        ->readonly()
        ->disabled() ?>
    <?= Field::email($form, 'email') ?>
    <?= Field::text($form, 'firstName') ?>
    <?= Field::text($form, 'lastName') ?>
    <?= Html::submitButton($t->translate('Update Profile'), ['class' => 'btn btn-primary']) ?>
<?= $htmlForm->close() ?>
