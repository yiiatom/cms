<?php

declare(strict_types=1);

namespace Atom\Web\User\Trash;

use Atom\Repository\UserRepository;
use Atom\Web\Shared\Breadcrumbs\BreadcrumbsProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $this->breadcrumbsProvider
            ->add('Users', 'atom.user.index')
            ->add('Trash');

        $dataReader = $this->userRepository->findAllDeletedAsDataReader();

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/trash', [
                'dataReader' => $dataReader,
            ]);
    }
}
