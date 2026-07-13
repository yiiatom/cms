<?php

declare(strict_types=1);

namespace Atom\Web\Profile\Edit;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\Length;

final class ProfileForm extends FormModel
{
    #[Label('Username')]
    public ?string $username = null;

    #[Label('Email')]
    #[Email(skipOnEmpty: true)]
    public ?string $email = null;

    #[Label('First Name')]
    #[Length(max: 100, skipOnEmpty: true)]
    public ?string $firstName = null;

    #[Label('Last Name')]
    #[Length(max: 100, skipOnEmpty: true)]
    public ?string $lastName = null;
}
