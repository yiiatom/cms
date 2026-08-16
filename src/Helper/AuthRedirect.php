<?php

declare(strict_types=1);

namespace Atom\Helper;

use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\SessionInterface;

final class AuthRedirect
{
    private const SESSION_KEY = 'auth_target_url';

    public function __construct(
        private SessionInterface $session,
        private UrlGeneratorInterface $urlGenerator,
        private ?string $defaultUrl = null,
    ) {}

    public function setTargetUrl(string $url): void
    {
        $this->session->set(self::SESSION_KEY, $url);
    }

    public function getTargetUrl(bool $clear = true): string
    {
        $url = $this->session->get(self::SESSION_KEY);

        if ($clear) {
            $this->session->remove(self::SESSION_KEY);
        }

        return $url ?? $this->defaultUrl ?? $this->urlGenerator->generate('atom.dashboard');
    }
}
