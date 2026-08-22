<?php

declare(strict_types=1);

namespace Atom\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareDispatcher;
use Yiisoft\User\Login\Cookie\CookieLoginMiddleware;

final class PipelineMiddleware implements MiddlewareInterface
{
    private readonly MiddlewareDispatcher $dispatcher;

    public function __construct(
        MiddlewareDispatcher $dispatcher,
    ) {
        $this->dispatcher = $dispatcher->withMiddlewares([
            MainThemeMiddleware::class,
            LocaleMiddleware::class,
            CookieLoginMiddleware::class,
            AuthenticationMiddleware::class,
        ]);
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface
    {
        return $this->dispatcher->dispatch($request, $handler);
    }
}
