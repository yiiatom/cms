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
    #[Integer]
    public ?int $status = null;

    #[Label('Role')]
    #[Integer]
    public ?int $role = null;

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
            UserStatus::PENDING->value => 'Pending',
            UserStatus::ACTIVE->value => 'Active',
            UserStatus::BLOCKED->value => 'Blocked',
            UserStatus::DELETED->value => 'Deleted',
        ];
    }

    public function getRoleOptions(): array
    {
        return [
            '' => 'All Roles',
            UserRole::ADMIN->value => 'Admin',
            UserRole::USER->value => 'User',
        ];
    }
}
