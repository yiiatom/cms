<?php

declare(strict_types=1);

namespace Atom\Web\User\Create;

use Atom\Entity\UserRole;
use Atom\Entity\UserStatus;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\In;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Regex;
use Yiisoft\Validator\Rule\Required;

final class UserCreateForm extends FormModel
{
    #[Label('Username')]
    #[Required]
    #[Length(min: 3, max: 20, skipOnEmpty: true)]
    #[Regex(
        pattern: '/^(?!\d+$)[a-zA-Z0-9_]+$/',
        skipOnEmpty: true,
        skipOnError: true,
    )]
    #[In(
        values: ['admin', 'administrator', 'support', 'root', 'moderator', 'help'],
        not: true,
        skipOnEmpty: true,
    )]
    public ?string $username = null;

    #[Label('Email')]
    #[Email(skipOnEmpty: true)]
    public ?string $email = null;

    #[Label('Status')]
    #[Required]
    public string $status = UserStatus::ACTIVE->value;

    #[Label('Role')]
    #[Required]
    public string $role = UserRole::USER->value;

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
        ];
    }

    public function getRoleOptions(): array
    {
        return [
            UserRole::USER->value => UserRole::USER->getLabel(),
            UserRole::ADMIN->value => UserRole::ADMIN->getLabel(),
        ];
    }
}
