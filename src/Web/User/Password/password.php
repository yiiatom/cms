<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 * @var UserPasswordForm $form
 */

$title = $t->translate('Change User Password');

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
    <?= Field::password($form, 'newPassword')->autofocus() ?>
    <?= Field::password($form, 'confirmPassword') ?>
    <?= Field::checkbox($form, 'requirePasswordChange') ?>
    <?= Html::submitButton($t->translate('Save'))
        ->class('btn btn-primary')
    ?>
    <?= Html::a($t->translate('Cancel'))
        ->url($urlGenerator->generate('atom.user.index'))
        ->class('btn btn-outline-secondary')
    ?>
<?= $htmlForm->close() ?>
