<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$title = 'Create User';

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-default form-user-create')
    ->post()
    ->csrf($csrf);

?>
<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'username') ?>
    <?= Field::email($form, 'email') ?>
    <?= Field::select($form, 'status')->optionsData($form->getStatusOptions()) ?>
    <?= Field::select($form, 'role')->optionsData($form->getRoleOptions()) ?>
    <?= Field::text($form, 'firstName') ?>
    <?= Field::text($form, 'lastName') ?>
    <?= Html::submitButton('Submit')->class('btn btn-primary') ?>
    <?= Html::a('Cancel')->url($urlGenerator->generate('atom.user.index'))->class('btn btn-outline-secondary') ?>
<?= $htmlForm->close() ?>
