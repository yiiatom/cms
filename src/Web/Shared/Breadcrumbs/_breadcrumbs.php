<?php

declare(strict_types=1);

/**
 * @var Yiisoft\Yii\View\Renderer\WebViewRenderer $this
 * @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator
 * @var Atom\Web\Shared\Breadcrumbs\Breadcrumbs $breadcrumbs
 */

use Yiisoft\Html\Html;

$items = $breadcrumbsProvider->getItems();

if (empty($items)) {
    return;
}
?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?= Html::a(
                Html::tag('i', '', ['class' => 'fa-solid fa-gauge']) . ' Dashboard',
                $urlGenerator->generate('atom.dashboard'),
            )->encode(false) ?>
        </li>
        
        <?php foreach ($items as $index => $item): ?>
            <?php if ($item->isLink()): ?>
                <li class="breadcrumb-item">
                    <?= Html::a(
                        Html::encode($item->getLabel()),
                        $urlGenerator->generate(
                            $item->getRouteName(),
                            $item->getRouteArguments(),
                            $item->getRouteQueryParameters(),
                            $item->getRouteHash(),
                        ),
                    ) ?>
                </li>
            <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= Html::encode($item->getLabel()) ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
