<?php

declare(strict_types=1);

namespace Atom\Mapper;

use Atom\Entity\SettingGroup;
use Closure;
use DateTimeImmutable;
use Yiisoft\Hydrator\HydratorInterface;

final class SettingGroupMapper
{
    public function __construct(
        private HydratorInterface $hydrator,
    ) {}

    public function mapRowToEntity(array $row): SettingGroup
    {
        $data = [
            'uuid' => $row['uuid'],
            'name' => $row['name'],
            'position' => (int) $row['position'],
            'createdAt' => new DateTimeImmutable($row['created_at']),
            'updatedAt' => new DateTimeImmutable($row['updated_at']),
        ];

        return $this->hydrator->create(SettingGroup::class, $data);
    }

    public function mapEntityToRow(SettingGroup $entity): array
    {
        $extractor = function (): array {
            return [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'position' => $this->position,
                'created_at' => $this->createdAt,
                'updated_at' => $this->updatedAt,
            ];
        };

        $extractorClosure = Closure::bind($extractor, $entity, SettingGroup::class);

        return $extractorClosure();
    }
}
