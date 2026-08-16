<?php

declare(strict_types=1);

namespace Atom\Web\User\Index;

use Atom\Entity\UserRole;
use Atom\Entity\UserStatus;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\StringValue;

class UserFilterForm extends FormModel
{
    #[Label('Username, email or name')]
    #[Length(max: 100)]
    #[StringValue]
    public ?string $search = null;

    #[Label('Status')]
    #[StringValue]
    public ?string $status = null;

    #[Label('Role')]
    #[StringValue]
    public ?string $role = null;

    public function getFormName(): string
    {
        return '';
    }

    public function getFilters(): array
    {
        return [
            'search' => $this->search,
            'status' => $this->status,
            'role' => $this->role,
        ];
    }

    public function getStatusOptions(): array
    {
        return [
            '' => 'All Statuses',
            UserStatus::PENDING->value => UserStatus::PENDING->getLabel(),
            UserStatus::ACTIVE->value => UserStatus::ACTIVE->getLabel(),
            UserStatus::BLOCKED->value => UserStatus::BLOCKED->getLabel(),
        ];
    }

    public function getRoleOptions(): array
    {
        return [
            '' => 'All Roles',
            UserRole::ADMIN->value => UserRole::ADMIN->getLabel(),
            UserRole::USER->value => UserRole::USER->getLabel(),
        ];
    }
}
