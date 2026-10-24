<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Group\Edit;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\SettingGroupRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private FlashInterface $flash,
        private FormHydrator $formHydrator,
        private ResponseFactoryInterface $responseFactory,
        private SettingGroupRepository $settingGroupRepository,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(
        #[RouteArgument('uuid')] string $uuid,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-settings');

        $group = $this->settingGroupRepository->findOneByUuid($uuid);
        if (!$group) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: $t->translate('Settings'),
                url: $this->urlGenerator->generate('atom.setting.index'),
            ),
            new Breadcrumb(
                label: $t->translate('Manage'),
                url: $this->urlGenerator->generate('atom.setting.manage.index'),
            ),
            new Breadcrumb(
                label: $group->getName(),
            ),
        );

        $form = (new GroupEditForm())->withTranslator($t);
        $form->name = $group->getName();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            $item = $this->settingGroupRepository->findOneByName($form->name);
            if ($item && $item->getUuid() !== $group->getUuid()) {
                $form->addError(
                    $t->translate('Name is already in use.'),
                    ['name'],
                );
            }
        }

        if ($form->isValid()) {
            $group
                ->setName($form->name);

            $this->settingGroupRepository->save($group);

            $this->flash->add(
                'success',
                $t->translate('Setting group has been updated.'),
            );

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.setting.manage.index'),
                );
        }

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/edit', [
                't' => $t,
                'form' => $form,
                'group' => $group,
            ]);
    }
}
