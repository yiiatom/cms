<?php

declare(strict_types=1);

use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @var SettingCreateForm $form
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 */

$title = $t->translate('Create Setting');

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-constrained')
    ->post()
    ->csrf($csrf);

?>
<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'name')->autofocus() ?>
    <?= Field::text($form, 'key')->autofocus() ?>
    <?= Field::select($form, 'type')->optionsData($form->getTypeOptions()) ?>
    <?= Html::submitButton($t->translate('Save'))
        ->class('btn btn-primary')
    ?>
    <?= Html::a($t->translate('Cancel'))
        ->url($urlGenerator->generate('atom.setting.manage.index'))
        ->class('btn btn-outline-secondary')
    ?>
<?= $htmlForm->close() ?>
