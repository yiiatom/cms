<?php

declare(strict_types=1);

use Atom\Web\Shared\Widget\SidebarMenuWidget;
use Yiisoft\Html\Html;

?>
<aside id="sidebar" class="atom-sidebar text-white bg-dark offcanvas-md offcanvas-start" tabindex="-1">
    <hr>
    <?= SidebarMenuWidget::widget()
        ->items($sidebarMenuProvider->getMenuItems())
        ->addListClass('nav nav-pills flex-column mb-auto')
        ->addItemClass('nav-item')
        ->addLinkClass('nav-link text-white')
        ->addIconClass('me-2')
    ?>
    <div class="small opacity-25 px-2">v0.1.0-dev</div>
    <hr>
    <div class="dropup current-user px-2">
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
