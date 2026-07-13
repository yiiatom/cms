<?php

declare(strict_types=1);

namespace Atom\Web\Users\Edit;

use Atom\Entity\UserStatus;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\In;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Regex;
use Yiisoft\Validator\Rule\Required;

final class UserEditForm extends FormModel
{
    #[Label('Username')]
    public ?string $username = null;

    #[Label('Email')]
    #[Email(skipOnEmpty: true)]
    public ?string $email = null;

    #[Label('Status')]
    #[Required]
    public int $status = UserStatus::ACTIVE->value;

    #[Label('First Name')]
    #[Length(max: 100, skipOnEmpty: true)]
    public ?string $firstName = null;

    #[Label('Last Name')]
    #[Length(max: 100, skipOnEmpty: true)]
    public ?string $lastName = null;

    public function getStatusOptions(): array
    {
        return [
            UserStatus::PENDING->value => UserStatus::PENDING->getLabel(),
            UserStatus::ACTIVE->value => UserStatus::ACTIVE->getLabel(),
            UserStatus::BLOCKED->value => UserStatus::BLOCKED->getLabel(),
            UserStatus::DELETED->value => UserStatus::DELETED->getLabel(),
        ];
    }
}
