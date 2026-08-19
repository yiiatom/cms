<?php

declare(strict_types=1);

namespace Atom\Helper;

use ReflectionClass;
use ReflectionProperty;
use Yiisoft\Translator\TranslatorInterface;

trait FormTranslatorTrait
{
    private ?TranslatorInterface $translator = null;

    private ?array $validationPropertyLabels = null;

    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    public function getTranslator(): ?TranslatorInterface
    {
        return $this->translator;
    }

    public function getPropertyLabel(string $property): string
    {
        $label = parent::getPropertyLabel($property);

        if ($this->translator) {
            $label = $this->translator->translate($label);
        }

        return $label;
    }

    public function getValidationPropertyLabels(): array
    {
        if ($this->validationPropertyLabels !== null) {
            return $this->validationPropertyLabels;
        }

        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $labels = [];
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $property->getName();
            $labels[$name] = $this->getPropertyLabel($name);
        }

        return $this->validationPropertyLabels = $labels;
    }
}
