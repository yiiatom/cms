<?php

declare(strict_types=1);

namespace Atom\Web\Translit;

use Atom\Helper\Translit;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;

final readonly class Action
{
    public function __construct(
        private DataResponseFactoryInterface $responseFactory,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $params = $request->getQueryParams();
        $text = $params['text'] ?? '';

        $result = Translit::t($text);

        return $this->responseFactory->createResponse([
            'text' => $text,
            'result' => $result,
        ])->withResponseFormatter(new JsonDataResponseFormatter());
    }
}
