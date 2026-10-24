<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Setting\Create;

use Atom\Entity\SettingType;
use Atom\Helper\FormTranslatorTrait;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\LabelsProviderInterface;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Regex;
use Yiisoft\Validator\Rule\Required;

final class SettingCreateForm extends FormModel implements LabelsProviderInterface
{
    use FormTranslatorTrait;

    #[Label('Name')]
    #[Required]
    #[Length(min: 3, max: 100, skipOnEmpty: true)]
    public ?string $name = null;

    #[Label('Key')]
    #[Required]
    #[Length(min: 3, max: 64, skipOnEmpty: true)]
    #[Regex(
        pattern: '/^[a-z][a-z0-9_\-]*(\.[a-z0-9_\-]+)*$/',
        skipOnEmpty: true,
        skipOnError: true,
    )]
    public ?string $key = null;

    #[Label('Type')]
    #[Required]
    public string $type = SettingType::STRING->value;

    public function getTypeOptions(): array
    {
        $t = $this->getTranslator();

        return [
            SettingType::STRING->value => $t->translate(SettingType::STRING->getLabel()),
            SettingType::BOOLEAN->value => $t->translate(SettingType::BOOLEAN->getLabel()),
            SettingType::NUMBER->value => $t->translate(SettingType::NUMBER->getLabel()),
            SettingType::TEXT->value => $t->translate(SettingType::TEXT->getLabel()),
            SettingType::CODE->value => $t->translate(SettingType::CODE->getLabel()),
            SettingType::IMAGE->value => $t->translate(SettingType::IMAGE->getLabel()),
            SettingType::FILE->value => $t->translate(SettingType::FILE->getLabel()),
        ];
    }
}
