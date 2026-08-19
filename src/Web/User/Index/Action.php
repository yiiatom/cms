<?php

declare(strict_types=1);

namespace Atom\Web\User\Index;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private FormHydrator $formHydrator,
        private TranslatorInterface $translator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-users');

        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: $t->translate('Users'),
            )
        );

        $form = new UserFilterForm();
        $form->setTranslator($t);

        $this->formHydrator->populateFromGet($form, $request);

        $dataReader = $this->userRepository->findAllAsDataReader($form->getFilters());
        $deletedCount = $this->userRepository->getDeletedCount();

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/index', [
                't' => $t,
                'form' => $form,
                'dataReader' => $dataReader,
                'deletedCount' => $deletedCount,
            ]);
    }
}
