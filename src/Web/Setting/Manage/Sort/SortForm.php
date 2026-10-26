<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Sort;

use Atom\Helper\FormTranslatorTrait;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\LabelsProviderInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Each;
use Yiisoft\Validator\Rule\In;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Uuid;
use Yiisoft\Validator\ValidationContext;
use Yiisoft\Validator\Validator;

final class SortForm extends FormModel implements LabelsProviderInterface
{
    use FormTranslatorTrait;

    #[Label('Element Type')]
    #[Required]
    #[In(['group', 'setting'], skipOnEmpty: true)]
    public ?string $type = null;

    #[Label('Group UUID')]
    #[Callback(method: 'validateGroupUuid')]
    public ?string $groupUuid = null;

    #[Label('Positions Map')]
    #[Callback(method: 'validatePositions')]
    public array $positions = [];

    public function getFormName(): string
    {
        return '';
    }

    private function validateGroupUuid(mixed $value): Result
    {
        $rules = [new Uuid(skipOnEmpty: true)];

        if ($this->type === 'setting') {
            $rules[] = new Required();
        }

        return (new Validator())->validate($this, ['groupUuid' => $rules]);
    }

    private function validatePositions(mixed $value, Callback $rule, ValidationContext $context): Result
    {
        $result = (new Validator())->validate($this, ['positions' => [new Required()]]);

        $indexRules = [new Uuid()];
        $itemRules = [new Integer(min: 1)];

        foreach ($this->positions as $index => $item) {
            $indexResult = $context->validate($index, $indexRules);
            if (!$indexResult->isValid()) {
                foreach ($indexResult->getErrors() as $error) {
                    $result->addErrorWithoutPostProcessing(
                        $error->getMessage(),
                        $error->getParameters(),
                        $error->getValuePath() === [] ? [$index] : [$index, ...$error->getValuePath()],
                    );
                }
            }

            $itemResult = $context->validate($item, $itemRules);
            if (!$itemResult->isValid()) {
                foreach ($itemResult->getErrors() as $error) {
                    $result->addErrorWithoutPostProcessing(
                        $error->getMessage(),
                        $error->getParameters(),
                        $error->getValuePath() === [] ? [$index] : [$index, ...$error->getValuePath()],
                    );
                }
            }
        }
        return $result;
    }
}