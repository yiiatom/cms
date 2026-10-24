<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Setting\Create;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Entity\Setting;
use Atom\Entity\SettingType;
use Atom\Repository\SettingGroupRepository;
use Atom\Repository\SettingRepository;
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
        private SettingRepository $settingRepository,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-settings');

        $group = null;
        $group_uuid = $request->getQueryParams()['group-uuid'] ?? null;
        if ($group_uuid) {
            $group = $this->settingGroupRepository->findOneByUuid($group_uuid);
        }
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
                label: $t->translate('Create Setting'),
            ),
        );

        $form = (new SettingCreateForm())->withTranslator($t);

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            if ($this->settingRepository->findOneByKey($form->key)) {
                $form->addError(
                    $t->translate('Key is already in use.'),
                    ['key'],
                );
            }
        }

        if ($form->isValid()) {
            $setting = Setting::create(
                group: $group,
                name: $form->name,
                key: $form->key,
                type: SettingType::from($form->type),
            );
            $this->settingRepository->save($setting);

            $this->flash->add(
                'success',
                $t->translate('Setting has been created.'),
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
