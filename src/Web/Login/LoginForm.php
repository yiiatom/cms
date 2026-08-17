<?php

declare(strict_types=1);

namespace Atom\Web\Login;

use Atom\Helper\FormTranslatorTrait;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\LabelsProviderInterface;
use Yiisoft\Validator\Rule\BooleanValue;
use Yiisoft\Validator\Rule\Required;

final class LoginForm extends FormModel implements LabelsProviderInterface
{
    use FormTranslatorTrait;

    #[Label('Username')]
    #[Required]
    public ?string $username = null;

    #[Label('Password')]
    #[Required]
    public ?string $password = null;

    #[Label('Remember Me')]
    #[BooleanValue]
    public bool $rememberMe = false;
}
