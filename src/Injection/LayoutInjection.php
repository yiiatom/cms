<?php

declare(strict_types=1);

namespace Atom\Injection;

use Atom\Web\Shared\Breadcrumbs\BreadcrumbsProvider;
use Atom\Web\Shared\Sidebar\SidebarMenuProvider;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\LayoutParametersInjectionInterface;

final readonly class LayoutInjection implements LayoutParametersInjectionInterface
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private CurrentRoute $currentRoute,
        private CurrentUser $currentUser,
        private SidebarMenuProvider $sidebarMenuProvider,
    ) {}

    public function getLayoutParameters(): array
    {
        return [
            'currentRoute' => $this->currentRoute,
            'userDisplayName' => $this->getDisplayName(),
            'userAvatarUrl' => $this->getAvatarUrl(),
            'sidebarMenuProvider' => $this->sidebarMenuProvider,
            'breadcrumbsProvider' => $this->breadcrumbsProvider,
        ];
    }

    private function getDisplayName(): string
    {
        if ($this->currentUser->isGuest()) {
            return 'Guest';
        }

        $user = $this->currentUser->getIdentity()->getUser();

        $firstName = $user->getFirstName() ?? "";
        $lastName = $user->getLastName() ?? "";

        $displayName = trim($firstName . ' ' . $lastName);

        if (!$displayName) {
            $displayName = $user->getUsername();
        }

        return $displayName;
    }

    private function getAvatarUrl(): ?string
    {
        if ($this->currentUser->isGuest()) {
            return null;
        }

        return $this->currentUser->getIdentity()->getUser()->getAvatarUrl();
    }
}
