<?php

declare(strict_types=1);

namespace Atom\Web\User\Password;

use Atom\Helper\FormTranslatorTrait;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\LabelsProviderInterface;
use Yiisoft\Validator\Rule\Required;

final class UserPasswordForm extends FormModel implements LabelsProviderInterface
{
    use FormTranslatorTrait;

    #[Label('Username')]
    public ?string $username = null;

    #[Label('New Password')]
    #[Required]
    public ?string $newPassword = null;

    #[Label('Confirm Password')]
    #[Required]
    public ?string $confirmPassword = null;

    #[Label('Require Password Change on Next Login')]
    public bool $requirePasswordChange = true;
}
