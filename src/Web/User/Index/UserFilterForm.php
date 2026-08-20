<?php

declare(strict_types=1);

namespace Atom\Web\User\Index;

use Atom\Entity\UserRole;
use Atom\Entity\UserStatus;
use Atom\Helper\FormTranslatorTrait;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\LabelsProviderInterface;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\StringValue;

class UserFilterForm extends FormModel implements LabelsProviderInterface
{
    use FormTranslatorTrait;

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
        $t = $this->getTranslator();

        return [
            '' => $t->translate('[All Statuses]'),
            UserStatus::PENDING->value => $t->translate(UserStatus::PENDING->getLabel()),
            UserStatus::ACTIVE->value => $t->translate(UserStatus::ACTIVE->getLabel()),
            UserStatus::BLOCKED->value => $t->translate(UserStatus::BLOCKED->getLabel()),
        ];
    }

    public function getRoleOptions(): array
    {
        $t = $this->getTranslator();

        return [
            '' => $t->translate('[All Roles]'),
            UserRole::ADMIN->value => $t->translate(UserRole::ADMIN->getLabel()),
            UserRole::USER->value => $t->translate(UserRole::USER->getLabel()),
        ];
    }
}
