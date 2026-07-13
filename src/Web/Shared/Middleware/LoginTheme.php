<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Form\Theme\ThemeContainer;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class LoginTheme implements MiddlewareInterface
{
    public function __construct(
        private Aliases $aliases,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        ThemeContainer::initialize([
            'login' => require $this->aliases->get('@atom/config/theme/login.php'),
        ], 'login');

        $renderer = $this->viewRenderer->withLayout('@atom/src/Web/Shared/Layout/Login/login');
        $request = $request->withAttribute(WebViewRenderer::class, $renderer);

        return $handler->handle($request);
    }
}
