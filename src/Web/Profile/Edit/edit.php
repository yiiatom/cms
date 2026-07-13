<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$title = 'Profile';

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-default form-profile')
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
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
<?= $htmlForm->close() ?>
