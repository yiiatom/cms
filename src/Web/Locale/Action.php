<?php

declare(strict_types=1);

namespace Atom\Web\Locale;

use Atom\Locale\LocaleContext;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;

final class Action
{
    public function __construct(
        private LocaleContext $localeContext,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $params = $request->getQueryParams();
        $locale = (string) ($params['lang'] ?? '');

        $referer = $request->getHeaderLine('Referer');
        $url = !empty($referer) ? $referer : $this->urlGenerator->generate('atom.dashboard');

        $response = $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(
                Header::LOCATION, 
                $this->urlGenerator->generate('atom.dashboard'),
            );

        return $this->localeContext->write($response, $locale);
    }
}
