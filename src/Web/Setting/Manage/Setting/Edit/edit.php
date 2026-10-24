<?php

declare(strict_types=1);

use Atom\Entity\Setting;
use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 * @var GroupEditForm $form
 * @var Setting $setting
 */

$title = $t->translate('Edit Setting');

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

<div class="mt-3">
    <?= Html::i($t->translate('Last Updated: {date}', [
        'date' => $setting->getUpdatedAt()->format('Y-m-d H:i:s'),
    ])) ?>
</div>
