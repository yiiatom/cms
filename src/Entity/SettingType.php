<?php

declare(strict_types=1);

namespace Atom\Entity;

enum SettingType: string
{
    case STRING = 'string';
    case TEXT = 'text';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case BOOLEAN = 'boolean';
    case CODE = 'code';
    case IMAGE = 'image';
    case FILE = 'file';

    public function getLabel(): string
    {
        return match ($this) {
            self::STRING => 'String',
            self::TEXT => 'Text',
            self::INTEGER => 'Integer',
            self::FLOAT => 'Float',
            self::BOOLEAN => 'Boolean',
            self::CODE => 'Code',
            self::IMAGE => 'Image',
            self::FILE => 'File',
        };
    }
}
