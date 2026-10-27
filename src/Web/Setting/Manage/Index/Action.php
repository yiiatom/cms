<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Index;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\SettingRepository;
use Atom\Repository\SettingGroupRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private SettingGroupRepository $settingGroupRepository,
        private SettingRepository $settingRepository,
        private SettingTreeBuilder $settingTreeBuilder,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-settings');

        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: $t->translate('Settings'),
                url: $this->urlGenerator->generate('atom.setting.index'),
            ),
            new Breadcrumb(
                label: $t->translate('Manage'),
            ),
        );

        $dataReader = $this->settingTreeBuilder->buildAsDataReader(
            $this->settingGroupRepository->findAll(),
            $this->settingRepository->findAll(),
        );

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/index', [
                't' => $t,
                'dataReader' => $dataReader,
            ]);
    }
}
