<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\FormModel\Field;

$this->setTitle('Login');

$htmlForm = Html::form()
    ->class('form-login')
    ->post()
    ->csrf($csrf);
?>

<?= $htmlForm->open() ?>

<div class="modal d-block" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-regular fa-user"></i> Login</h5>
            </div>
            <div class="modal-body pb-0">
                <?= Field::text($form, 'username')
                    ->placeholder($form->getPropertyLabel('username')) ?>
                <?= Field::password($form, 'password')
                    ->placeholder($form->getPropertyLabel('password')) ?>
                <?= Field::checkbox($form, 'rememberMe') ?>

                <?php if ($form->isValidated() && !$form->isValid()): ?>
                    <div class="alert alert-danger mb-2" role="alert">
                        <?= Html::encode($form->getValidationResult()->getErrorMessages()[0]) ?>
                    </div>
                <?php endif; ?>

            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>

<?= $htmlForm->close() ?>
