<?php

declare(strict_types=1);

namespace Atom\Middleware;

use Atom\Locale\LocaleContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Translator\TranslatorInterface;

final class LocaleMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LocaleContext $localeContext,
        private TranslatorInterface $translator,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface
    {
        $locale = $this->localeContext->read($request);
        $this->translator->setLocale($locale);

        return $handler->handle($request->withAttribute(LocaleContext::class, $this->localeContext));
    }
}
