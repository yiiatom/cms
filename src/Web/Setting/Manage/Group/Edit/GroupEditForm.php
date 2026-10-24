<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Group\Edit;

use Atom\Helper\FormTranslatorTrait;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\LabelsProviderInterface;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class GroupEditForm extends FormModel implements LabelsProviderInterface
{
    use FormTranslatorTrait;

    #[Label('Name')]
    #[Required]
    #[Length(min: 1, max: 100, skipOnEmpty: true)]
    public ?string $name = null;
}
