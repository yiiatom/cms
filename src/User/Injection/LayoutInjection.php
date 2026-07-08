<?php

declare(strict_types=1);

namespace Atom\User\Injection;

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

        $identity = $this->currentUser->getIdentity();

        $firstName = $identity->firstName ?? "";
        $lastName = $identity->lastName ?? "";

        $displayName = trim($firstName . ' ' . $lastName);

        if (!$displayName) {
            $displayName = $identity->username;
        }

        return $displayName;
    }

    private function getAvatarUrl(): ?string
    {
        if ($this->currentUser->isGuest()) {
            return null;
        }

        return $this->currentUser->getIdentity()->avatarUrl;
    }
}
