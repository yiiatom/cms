<?php

declare(strict_types=1);

namespace Atom\Web\User\Password;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Compare;
use Yiisoft\Validator\Rule\Required;

final class UserPasswordForm extends FormModel
{
    #[Label('New Password')]
    #[Required]
    public ?string $newPassword = null;

    #[Label('Confirm')]
    #[Required]
    #[Compare(targetProperty: 'newPassword', message: 'Passwords do not match.')]
    public ?string $confirmPassword = null;

    #[Label('Require Password Change on Next Login')]
    public bool $requirePasswordChange = true;
}
