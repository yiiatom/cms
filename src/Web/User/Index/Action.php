<?php

declare(strict_types=1);

namespace Atom\Web\User\Index;

use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $dataReader = $this->userRepository->findAllDataReader();

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/index', [
                'dataReader' => $dataReader,
            ]);
    }
}
