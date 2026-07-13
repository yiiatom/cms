<?php

declare(strict_types=1);

namespace Atom\Web\Profile\ChangePassword;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Compare;
use Yiisoft\Validator\Rule\Required;

final class ChangePasswordForm extends FormModel
{
    #[Label('Old Password')]
    #[Required]
    public ?string $oldPassword = null;

    #[Label('New Password')]
    #[Required]
    public ?string $newPassword = null;

    #[Label('Confirm')]
    #[Required]
    #[Compare(targetProperty: 'newPassword', message: 'Passwords do not match.')]
    public ?string $confirmPassword = null;
}
