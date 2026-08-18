<?php

declare(strict_types=1);

namespace Atom\Web\User\Trash;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: 'Users',
                url: $this->urlGenerator->generate('atom.user.index'),
            ),
            new Breadcrumb(
                label: 'Trash',
            ),
        );

        $dataReader = $this->userRepository->findAllDeletedAsDataReader();

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/trash', [
                'dataReader' => $dataReader,
            ]);
    }
}
