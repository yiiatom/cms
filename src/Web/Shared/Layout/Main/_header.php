<?php

declare(strict_types=1);

use Atom\Locale\LocaleContext;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;

/**
 * @var LocaleContext $localeContext
 * @var TranslatorInterface $t
 * @var UrlGeneratorInterface $urlGenerator
 * @var string $userAvatarUrl
 * @var string $userDisplayName
 */

?>
<header class="atom-header fixed-top text-white bg-dark">
    <button type="button"
        class="sidebar-toggler d-md-none"
        data-bs-toggle="offcanvas"
        data-bs-target="#sidebar"
        aria-controls="sidebar"
        aria-expanded="false">
        <span></span>
    </button>

    <?= Html::a('<div class="logo me-2"></div><span>Atom</span>')
        ->encode(false)
        ->url($urlGenerator->generate('atom.dashboard'))
        ->class('brand text-white text-decoration-none fs-4 me-2')
    ?>

    <div class="d-flex align-items-center flex-nowrap ms-auto">
        <div class="dropdown">
            <a href="#"
                class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <span><?= Html::encode($localeContext->getCurrentLocale()->getCode()) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <?php foreach ($localeContext->getLocales() as $item): ?>
                    <li>
                        <?= Html::a($item->getLabel())
                            ->url($urlGenerator->generate('atom.locale', ['lang' => $item->getCode()]))
                            ->class($item->isActive() ? 'dropdown-item active' : 'dropdown-item')
                            ->encode(false) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="vr mx-2"></div>

        <div class="dropdown current-user">
            <a href="#"
                class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="avatar">
                    <?php if ($userAvatarUrl): ?>
                        <img src="<?= $userAvatarUrl ?>" alt="">
                    <?php else: ?>
                        <i class="fa-regular fa-user"></i>
                    <?php endif; ?>
                </div>
                <strong class="d-none d-md-inline-block ms-2"><?= Html::encode($userDisplayName) ?></strong>
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
    </div>
</header>
