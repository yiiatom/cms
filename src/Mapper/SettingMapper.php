<?php

declare(strict_types=1);

namespace Atom\Mapper;

use Atom\Entity\Setting;
use Atom\Entity\SettingType;
use Closure;
use DateTimeImmutable;
use Yiisoft\Hydrator\HydratorInterface;

final class SettingMapper
{
    public function __construct(
        private HydratorInterface $hydrator,
    ) {}

    public function mapRowToEntity(array $row): Setting
    {
        $type = SettingType::from($row['type']);
        $v = $row['value'];
        $value = match ($type) {
            SettingType::BOOLEAN => (bool) $v,
            SettingType::INTEGER => (int) $v,
            SettingType::FLOAT => (float) $v,
            SettingType::STRING,
            SettingType::TEXT,
            SettingType::CODE,
            SettingType::IMAGE,
            SettingType::FILE => $v,
        };

        $data = [
            'uuid' => $row['uuid'],
            'groupUuid' => $row['group_uuid'],
            'key' => $row['key'],
            'name' => $row['name'],
            'position' => (int) $row['position'],
            'type' => $type,
            'value' => $value,
            'createdAt' => new DateTimeImmutable($row['created_at']),
            'updatedAt' => new DateTimeImmutable($row['updated_at']),
        ];

        return $this->hydrator->create(Setting::class, $data);
    }

    public function mapEntityToRow(Setting $entity): array
    {
        $extractor = function (): array {
            return [
                'uuid' => $this->uuid,
                'group_uuid' => $this->groupUuid,
                'key' => $this->key,
                'name' => $this->name,
                'position' => $this->position,
                'type' => $this->type->value,
                'value' => (string) $this->value,
                'created_at' => $this->createdAt,
                'updated_at' => $this->updatedAt,
            ];
        };

        $extractorClosure = Closure::bind($extractor, $entity, Setting::class);

        return $extractorClosure();
    }
}
