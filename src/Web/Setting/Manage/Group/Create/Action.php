<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Group\Create;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Entity\SettingGroup;
use Atom\Repository\SettingGroupRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
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
                url: $this->urlGenerator->generate('atom.setting.manage.index'),
            ),
            new Breadcrumb(
                label: $t->translate('Create Group'),
            ),
        );

        $form = (new GroupCreateForm())->withTranslator($t);

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            if ($this->settingGroupRepository->findOneByName($form->name)) {
                $form->addError(
                    $t->translate('Name is already in use.'),
                    ['name'],
                );
            }
        }

        if ($form->isValid()) {
            $group = SettingGroup::create(
                name: $form->name,
            );
            $this->settingGroupRepository->save($group);

            $this->flash->add(
                'success',
                $t->translate('Setting group has been created.'),
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
            ->render(__DIR__ . '/create', [
                't' => $t,
                'form' => $form,
            ]);
    }
}
