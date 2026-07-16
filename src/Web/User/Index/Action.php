<?php

declare(strict_types=1);

namespace Atom\Web\User\Index;

use Atom\Repository\UserRepository;
use Atom\Web\Shared\Breadcrumbs\BreadcrumbsProvider;
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
        $this->breadcrumbsProvider->add('Users');

        $form = new UserFilterForm();
        $this->formHydrator->populateFromGet($form, $request);

        $dataReader = $this->userRepository->findAllAsDataReader($form->getFilters());

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/index', [
                'form' => $form,
                'dataReader' => $dataReader,
            ]);
    }
}
