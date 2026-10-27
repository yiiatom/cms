<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Index;

use Atom\Data\SettingDataReader;
use Atom\Entity\Setting;
use Atom\Entity\SettingGroup;
use Yiisoft\Data\Reader\ReadableDataInterface;

final class SettingTreeBuilder
{
    /**
     * @param array<SettingGroup> $groups
     * @param array<Setting> $settings
     * @psalm-return ReadableDataInterface
     */
    public function buildAsDataReader(array $groups, array $settings): ReadableDataInterface
    {
        $grouped = [];
        foreach ($settings as $setting) {
            $grouped[$setting->getGroupUuid()][] = $setting;
        }

        $items = [];
        foreach ($groups as $group) {
            $items[] = $group;
            $items = array_merge($items, $grouped[$group->getUuid()] ?? []);
        }

        return new SettingDataReader($items);
    }
}
