<?php

declare(strict_types=1);

use Atom\Entity\User;
use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 * @var UserEditForm $form
 * @var User $user
 */

$title = $t->translate('Edit User');

$this->setTitle($title);

$htmlForm = Html::form()
    ->class('form-default form-user-edit')
    ->post()
    ->csrf($csrf);

?>
<h1><?= Html::encode($title) ?></h1>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'username')
        ->readonly()
        ->disabled() ?>
    <?= Field::email($form, 'email') ?>
    <?= Field::select($form, 'status')->optionsData($form->getStatusOptions()) ?>
    <?= Field::select($form, 'role')->optionsData($form->getRoleOptions()) ?>
    <?= Field::text($form, 'firstName') ?>
    <?= Field::text($form, 'lastName') ?>
    <?= Html::submitButton(
        $t->translate('Save'))
        ->class('btn btn-primary')
    ?>
    <?= Html::a($t->translate('Cancel'))
        ->url($urlGenerator->generate('atom.user.index'))
        ->class('btn btn-outline-secondary')
    ?>
<?= $htmlForm->close() ?>

<div class="mt-3">
    <?= Html::i($t->translate('Last Updated: {date}', [
        'date' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
    ])) ?>
</div>
