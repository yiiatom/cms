<?php

declare(strict_types=1);

namespace Atom\Helper;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\DataView\Url\UrlParameterProviderInterface;
use Yiisoft\Yii\DataView\Url\UrlParameterType;

final class DataViewUrlProvider implements UrlParameterProviderInterface
{
    public function __construct(
        private CurrentRoute $currentRoute,
        private ServerRequestInterface $request,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function get(string $name, UrlParameterType $type): ?string
    {
        if ($type === UrlParameterType::Query) {
            return $this->request->getQueryParams()[$name] ?? null;
        } elseif ($type === UrlParameterType::Path) {
            return $this->currentRoute->getArgument($name);
        }

        return null;
    }

    public function urlCreator(array $arguments, array $queryParameters): string
    {
        $name = $this->currentRoute->getName();

        return $this->urlGenerator->generate($name, $arguments, array_merge(
            $this->request->getQueryParams(),
            $queryParameters,
        ));
    }
}
