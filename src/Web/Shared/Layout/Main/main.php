<?php

declare(strict_types=1);

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Breadcrumbs\BreadcrumbsWidget;
use Atom\Web\Shared\Layout\Main\MainAsset;
use Atom\Web\Shared\Widget\AlertWidget;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\View\WebView;

/**
 * @var BreadcrumbsProvider $breadcrumbsProvider
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 * @var WebView $this
 */

$assetManager->register(MainAsset::class);

$this->addCssFiles($assetManager->getCssFiles());
$this->addCssStrings($assetManager->getCssStrings());
$this->addJsFiles($assetManager->getJsFiles());
$this->addJsStrings($assetManager->getJsStrings());
$this->addJsVars($assetManager->getJsVars());

$this->beginPage()
?>
<!DOCTYPE html>
<html lang="<?= Html::encode($applicationParams->locale) ?>">
<head>
    <meta charset="<?= Html::encode($applicationParams->charset) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= $aliases->get('@baseUrl/favicon.svg') ?>" type="image/svg+xml">
    <title><?= Html::encode($this->getTitle()) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('./_sidebar') ?>

<main class="main-container pt-md-2">
    <?= BreadcrumbsWidget::widget()
        ->breadcrumbs($breadcrumbsProvider->getBreadcrumbs())
        ->home(new Breadcrumb(
            label: Html::i('', ['class' => 'fa-solid fa-gauge me-1']) . $t->translate('Dashboard'),
            url: $urlGenerator->generate('atom.dashboard'),
            encode: false,
        ))
        ->addContainerClass('mb-3')
        ->addListClass('breadcrumb')
        ->addItemClass('breadcrumb-item')
        ->activeItemClass('active')
    ?>
    <?= AlertWidget::widget() ?>
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
