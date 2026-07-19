<?php

declare(strict_types=1);

use Yiisoft\Html\Html;

$currentRouteName = $currentRoute->getName();

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
        <?= Html::a('<span>Atom</span>')
            ->encode(false)
            ->url($urlGenerator->generate('atom.dashboard'))
            ->class('text-white text-decoration-none fs-4') ?>
        <span class="ver">0.1.0-dev</span>

        <?= Html::button('', [
            'class' => 'btn-close btn-close-white d-md-none ms-3',
            'data-bs-dismiss' => 'offcanvas',
            'data-bs-target' => '#sidebar',
            'aria-label' => 'Close',
        ]) ?>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <?php foreach ($sidebarMenuProvider->getMenuItems() as $item): ?>
            <?php
                $class = 'nav-link text-white';
                if ($item->isActive($currentRouteName)) {
                    $class .= ' active';
                }
            ?>
            <li class="nav-item">
                <?= Html::a(Html::tag('i', '', ['class' => $item->getIcon() . ' me-2']) . Html::encode($item->getLabel()))
                    ->encode(false)
                    ->url($urlGenerator->generate($item->getRouteName()))
                    ->class($class) ?>
            </li>
        <?php endforeach; ?>
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
