<?php

declare(strict_types=1);

namespace Atom\Web\Profile\ChangePassword;

use Atom\Helper\FormTranslatorTrait;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\LabelsProviderInterface;
use Yiisoft\Validator\Rule\Compare;
use Yiisoft\Validator\Rule\Required;

final class ChangePasswordForm extends FormModel implements LabelsProviderInterface
{
    use FormTranslatorTrait;

    #[Label('Current Password')]
    #[Required]
    public ?string $currentPassword = null;

    #[Label('New Password')]
    #[Required]
    public ?string $newPassword = null;

    #[Label('Confirm')]
    #[Required]
    public ?string $confirmPassword = null;
}
