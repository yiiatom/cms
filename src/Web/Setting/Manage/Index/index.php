<?php

declare(strict_types=1);

use Atom\Entity\Setting;
use Atom\Entity\SettingGroup;
use Atom\Web\Setting\Manage\Index\SettingsManageAsset;
use Yiisoft\Data\Reader\ReadableDataInterface;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;

/**
 * @var ReadableDataInterface $dataReader
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 */

$assetManager->register(SettingsManageAsset::class);

$title = $t->translate('Manage');

$this->setTitle($title);

?>
<h1><?= Html::encode($title) ?></h1>

<div class="mb-2 d-flex justify-content-between align-items-center">
    <?= Html::a($t->translate('Create Group'))
        ->url($urlGenerator->generate('atom.setting.manage.group.create'))
        ->class('btn btn-primary me-2')
    ?>
</div>

<?= GridView::widget()
    ->dataReader($dataReader)
    ->tbodyAttributes(['data-sort-url' => $urlGenerator->generate('atom.setting.manage.sort')])
    ->addTbodyClass('sortable-settings')
    ->bodyRowAttributes(static function (Setting|SettingGroup $item): array {
        return [
            'data-uuid' => $item->getUuid(),
            'data-type' => $item instanceof SettingGroup ? 'group' : 'setting',
        ];
    })
    ->columns(
        new DataColumn(
            bodyAttributes: ['style' => 'width: 40px'],
            content: static function (Setting|SettingGroup $item): string {
                return Html::i()
                    ->class('fa-solid fa-grip-vertical opacity-50 sort-handle')
                    ->render();
            },
            encodeContent: false,
        ),
        new DataColumn(
            property: 'name',
            header: $t->translate('Name'),
            content: static function (Setting|SettingGroup $item): string {
                $depth = $item instanceof SettingGroup ? 0 : 1;

                $padding = $depth * 24;

                if ($depth > 0) {
                    $icon = '<i class="fa-solid fa-turn-up fa-rotate-90 text-muted opacity-50 small me-2" style="font-size: 0.8rem;"></i>';
                } else {
                    $icon = '<i class="fa-solid fa-folder text-primary opacity-75 me-2"></i>';
                }

                return Html::div($icon . Html::encode($item->getName()))
                    ->encode(false)
                    ->addStyle('padding-left: ' . $padding . 'px')
                    ->render();
            },
            encodeContent: false,
        ),
        new DataColumn(
            header: $t->translate('Key'),
            content: static function (Setting|SettingGroup $item) use ($t): string {
                if ($item instanceof SettingGroup) {
                    return '';
                }

                return $t->translate($item->getKey());
            },
        ),
        new DataColumn(
            header: $t->translate('Type'),
            content: static function (Setting|SettingGroup $item) use ($t): string {
                if ($item instanceof SettingGroup) {
                    return '';
                }

                $type = $item->getType();

                return $t->translate($type->getLabel());
            },
        ),
        new ActionColumn(
            buttons: [
                'edit' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-pencil']),
                    title: $t->translate('Edit'),
                ),
                'delete' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-trash']),
                    title: $t->translate('Delete'),
                    attributes: static function (Setting|SettingGroup $item) use ($t): array {
                        $confirm = $item instanceof SettingGroup
                            ? 'Are you sure you want to delete this group? All its settings will also be deleted.'
                            : 'Are you sure you want to delete this setting?';

                        return [
                            'data-method' => 'POST',
                            'data-confirm' => $t->translate($confirm),
                        ];
                    },
                ),
                'create' => new ActionButton(
                    Html::i('', ['class' => 'fa-solid fa-folder-plus']),
                    url: static function (Setting|SettingGroup $item) use ($urlGenerator): string {
                        if ($item instanceof SettingGroup) {
                            return $urlGenerator->generate('atom.setting.manage.setting.create', ['group-uuid' => $item->getUuid()]);
                        } else {
                            return '#';
                        }
                    },
                    title: $t->translate('Create Setting Here'),
                ),
            ],
            urlCreator: function ($action, $context) use ($urlGenerator) {
                $baseRoute = $context->data instanceof Setting
                    ? 'atom.setting.manage.setting.'
                    : 'atom.setting.manage.group.';

                return $urlGenerator->generate($baseRoute . $action, ['uuid' => $context->data->getUuid()]);
            },
            visibleButtons: [
                'create' => static fn (Setting|SettingGroup $item): bool => $item instanceof SettingGroup,
                'edit' => true,
                'delete' => true,
            ],
        ),
    )
?>
