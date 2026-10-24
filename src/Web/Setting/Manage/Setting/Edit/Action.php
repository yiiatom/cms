<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Setting\Edit;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Entity\SettingType;
use Atom\Repository\SettingRepository;
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
        private SettingRepository $settingRepository,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(
        #[RouteArgument('uuid')] string $uuid,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-settings');

        $setting = $this->settingRepository->findOneByUuid($uuid);
        if (!$setting) {
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
                label: $setting->getName(),
            ),
        );

        $form = (new SettingEditForm())->withTranslator($t);
        $form->name = $setting->getName();
        $form->key = $setting->getKey();
        $form->type = $setting->getType()->value;

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            $item = $this->settingRepository->findOneByKey($form->key);
            if ($item && $item->getUuid() !== $setting->getUuid()) {
                $form->addError(
                    $t->translate('Key is already in use.'),
                    ['key'],
                );
            }
        }

        if ($form->isValid()) {
            $setting
                ->setName($form->name)
                ->setKey($form->key)
                ->setType(SettingType::from($form->type));

            $this->settingRepository->save($setting);

            $this->flash->add(
                'success',
                $t->translate('Setting has been updated.'),
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
                'setting' => $setting,
            ]);
    }
}
