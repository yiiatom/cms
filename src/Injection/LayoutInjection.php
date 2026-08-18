<?php

declare(strict_types=1);

namespace Atom\Injection;

use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Web\Shared\Sidebar\SidebarMenuProvider;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\LayoutParametersInjectionInterface;

final readonly class LayoutInjection implements LayoutParametersInjectionInterface
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private CurrentRoute $currentRoute,
        private CurrentUser $currentUser,
        private SidebarMenuProvider $sidebarMenuProvider,
        private TranslatorInterface $translator,
    ) {}

    public function getLayoutParameters(): array
    {
        return [
            'currentRoute' => $this->currentRoute,
            'userDisplayName' => $this->getDisplayName(),
            'userAvatarUrl' => $this->getAvatarUrl(),
            'sidebarMenuProvider' => $this->sidebarMenuProvider,
            'breadcrumbsProvider' => $this->breadcrumbsProvider,
            't' => $this->translator->withDefaultCategory('atom-cms'),
        ];
    }

    private function getDisplayName(): string
    {
        if ($this->currentUser->isGuest()) {
            return 'Guest';
        }

        return $this->currentUser
            ->getIdentity()
            ->getUser()
            ->getDisplayName();
    }

    private function getAvatarUrl(): ?string
    {
        if ($this->currentUser->isGuest()) {
            return null;
        }

        return $this->currentUser->getIdentity()->getUser()->getAvatarUrl();
    }
}
