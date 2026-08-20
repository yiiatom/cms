<?php

declare(strict_types=1);

use Atom\Web\Shared\Widget\SidebarMenuWidget;
use Yiisoft\Html\Html;

?>
<div class="navbar navbar-dark bg-dark d-md-none w-100 px-3 fixed-top shadow">
    <?= Html::a('<span>Atom</span>')
        ->encode(false)
        ->url($urlGenerator->generate('atom.dashboard'))
        ->class('text-white text-decoration-none fs-4') ?>

    <?= Html::button('<span class="navbar-toggler-icon"></span>', [
        'class' => 'navbar-toggler p-1',
        'data-bs-toggle' => 'offcanvas',
        'data-bs-target' => '#sidebar',
        'aria-controls' => 'sidebar',
    ])->encode(false)  ?>
</div>

<aside id="sidebar" class="sidebar text-white bg-dark offcanvas-md offcanvas-end" tabindex="-1">
    <div class="sidebar-header">
        <?= Html::a('<div class="logo me-2"></div><span>Atom</span>')
            ->encode(false)
            ->url($urlGenerator->generate('atom.dashboard'))
            ->class('brand text-white text-decoration-none fs-4') ?>
        <span class="ver">0.1.0-dev</span>

        <?= Html::button('', [
            'class' => 'btn-close btn-close-white d-md-none ms-3',
            'data-bs-dismiss' => 'offcanvas',
            'data-bs-target' => '#sidebar',
            'aria-label' => 'Close',
        ]) ?>
    </div>
    <hr>
    <?= SidebarMenuWidget::widget()
        ->items($sidebarMenuProvider->getMenuItems())
        ->addListClass('nav nav-pills flex-column mb-auto')
        ->addItemClass('nav-item')
        ->addLinkClass('nav-link text-white')
        ->addIconClass('me-2')
    ?>
    <hr>
    <div class="dropup current-user">
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
                <?= Html::a($t->translate('Profile'))
                    ->url($urlGenerator->generate('atom.profile.edit'))
                    ->class('dropdown-item') ?>
            </li>
            <li>
                <?= Html::a($t->translate('Change Password'))
                    ->url($urlGenerator->generate('atom.profile.change-password'))
                    ->class('dropdown-item') ?>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <?= Html::a($t->translate('Log Out'))
                    ->url($urlGenerator->generate('atom.logout'))
                    ->class('dropdown-item') ?>
            </li>
        </ul>
    </div>
</aside>
