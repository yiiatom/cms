<?php

declare(strict_types=1);

namespace Atom\Mapper;

use Atom\Entity\UserAuthKey;
use Closure;
use DateTimeImmutable;
use Yiisoft\Hydrator\HydratorInterface;

final class UserAuthKeyMapper
{
    public function __construct(
        private HydratorInterface $hydrator,
    ) {}

    public function mapRowToEntity(array $row): UserAuthKey
    {
        $data = [
            'uuid' => $row['uuid'],
            'userUuid' => $row['user_uuid'],
            'expiresAt' => new DateTimeImmutable($row['expires_at']),
        ];

        return $this->hydrator->create(UserAuthKey::class, $data);
    }

    public function mapEntityToRow(UserAuthKey $entity): array
    {
        $extractor = function (): array {
            return [
                'uuid' => $this->uuid,
                'user_uuid' => $this->userUuid,
                'expires_at' => $this->expiresAt,
            ];
        };

        $extractorClosure = Closure::bind($extractor, $entity, UserAuthKey::class);

        return $extractorClosure();
    }
}
