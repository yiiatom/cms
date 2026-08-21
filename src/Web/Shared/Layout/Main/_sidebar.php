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
</aside>
