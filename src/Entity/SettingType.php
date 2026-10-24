<?php

declare(strict_types=1);

namespace Atom\Entity;

enum SettingType: string
{
    case STRING = 'string';
    case TEXT = 'text';
    case NUMBER = 'number';
    case BOOLEAN = 'boolean';
    case CODE = 'code';
    case IMAGE = 'image';
    case FILE = 'file';

    public function getLabel(): string
    {
        return match ($this) {
            self::STRING => 'String',
            self::TEXT => 'Text',
            self::NUMBER => 'Number',
            self::BOOLEAN => 'Boolean',
            self::CODE => 'Code',
            self::IMAGE => 'Image',
            self::FILE => 'File',
        };
    }
}
