<?php

declare(strict_types=1);

namespace Atom\Web\User\Index;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private FormHydrator $formHydrator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: 'Users',
            )
        );

        $form = new UserFilterForm();
        $this->formHydrator->populateFromGet($form, $request);

        $dataReader = $this->userRepository->findAllAsDataReader($form->getFilters());
        $deletedCount = $this->userRepository->getDeletedCount();

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/index', [
                'form' => $form,
                'dataReader' => $dataReader,
                'deletedCount' => $deletedCount,
            ]);
    }
}
