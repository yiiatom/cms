<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer
            ->withLayout('@atom/src/Web/Shared/Layout/Main/layout')
            ->render(__DIR__ . '/dashboard');
    }
}
