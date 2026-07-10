<?php

declare(strict_types=1);

namespace Atom\Injection;

use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\LayoutParametersInjectionInterface;

final readonly class LayoutInjection implements LayoutParametersInjectionInterface
{
    public function __construct(
        private CurrentUser $currentUser,
    ) {}

    public function getLayoutParameters(): array
    {
        return [
            'userDisplayName' => $this->getDisplayName(),
            'userAvatarUrl' => $this->getAvatarUrl(),
        ];
    }

    private function getDisplayName(): string
    {
        if ($this->currentUser->isGuest()) {
            return 'Guest';
        }

        $user = $this->currentUser->getIdentity()->getUser();

        $firstName = $user->firstName ?? "";
        $lastName = $user->lastName ?? "";

        $displayName = trim($firstName . ' ' . $lastName);

        if (!$displayName) {
            $displayName = $user->username;
        }

        return $displayName;
    }

    private function getAvatarUrl(): ?string
    {
        if ($this->currentUser->isGuest()) {
            return null;
        }

        return $this->currentUser->getIdentity()->getUser()->avatarUrl;
    }
}
