<?php

declare(strict_types=1);

use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 * @var UserCreateForm $form
 */

$title = $t->translate('Add User');

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-default form-user-create')
    ->post()
    ->csrf($csrf);

?>
<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'username')->autofocus() ?>
    <?= Field::email($form, 'email') ?>
    <?= Field::select($form, 'status')->optionsData($form->getStatusOptions()) ?>
    <?= Field::select($form, 'role')->optionsData($form->getRoleOptions()) ?>
    <?= Field::text($form, 'firstName') ?>
    <?= Field::text($form, 'lastName') ?>
    <?= Html::submitButton($t->translate('Save'))
        ->class('btn btn-primary')
    ?>
    <?= Html::a($t->translate('Cancel'))
        ->url($urlGenerator->generate('atom.user.index'))
        ->class('btn btn-outline-secondary')
    ?>
<?= $htmlForm->close() ?>
