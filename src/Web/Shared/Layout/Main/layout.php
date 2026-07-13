<?php

declare(strict_types=1);

use Atom\Web\Shared\Layout\Main\Asset;
use Atom\Widget\AlertWidget;
use Yiisoft\Html\Html;

$assetManager->register(Asset::class);

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

<aside class="sidebar text-white bg-dark">
    <div class="sidebar-header">
        <?= Html::a('<span>Atom</span>')
            ->encode(false)
            ->url($urlGenerator->generate('atom.dashboard'))
            ->class('text-white text-decoration-none fs-4') ?>
        <span class="ver">0.1.0-dev</span>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <?= Html::a('<i class="fa-solid fa-tachograph-digital"></i> Dashboard')
                ->encode(false)
                ->url($urlGenerator->generate('atom.dashboard'))
                ->class('nav-link active') ?>
        </li>
        <li class="nav-item">
            <?= Html::a('<i class="fa-solid fa-user-group"></i> Users')
                ->encode(false)
                ->url($urlGenerator->generate('atom.user.list'))
                ->class('nav-link text-white') ?>
        </li>
    </ul>
    <hr>
    <div class="dropdown current-user">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="avatar">
                <?php if ($userAvatarUrl): ?>
                    <img src="<?= $userAvatarUrl ?>" alt="">
                <?php else: ?>
                    <i class="fa-regular fa-user"></i>
                <?php endif; ?>
            </div>
            <strong><?= Html::encode($userDisplayName) ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li>
                <?= Html::a('Profile')
                    ->url($urlGenerator->generate('atom.profile.edit'))
                    ->class('dropdown-item') ?>
            </li>
            <li>
                <?= Html::a('Change password')
                    ->url($urlGenerator->generate('atom.profile.change-password'))
                    ->class('dropdown-item') ?>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <?= Html::a('Log out')
                    ->url($urlGenerator->generate('atom.logout'))
                    ->class('dropdown-item') ?>
            </li>
        </ul>
    </div>
</aside>

<main class="main-container">
    <?= AlertWidget::widget() ?>
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
