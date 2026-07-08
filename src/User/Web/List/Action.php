<?php

declare(strict_types=1);

namespace Atom\User\Web\List;

use Atom\User\Data\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private UserRepository $userRepository,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(): ResponseInterface
    {
        // $dataReader = new QueryDataReader($this->userRepository->findAllQuery());
        $dataReader = $this->userRepository->findAllDataReader();

        return $this->viewRenderer
            ->withLayout('@atom/src/Web/Shared/Layout/Main/layout')
            ->render(__DIR__ . '/template', [
                'dataReader' => $dataReader,
            ]);
    }
}
