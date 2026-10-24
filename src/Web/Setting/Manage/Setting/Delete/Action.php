<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Setting\Delete;

use Atom\Repository\SettingRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Translator\TranslatorInterface;

final readonly class Action
{
    public function __construct(
        private FlashInterface $flash,
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

        $this->settingRepository->delete($setting);

        $this->flash->add('success', $t->translate('Setting has been deleted.'));

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(
                'Location', 
                $this->urlGenerator->generate('atom.setting.manage.index'),
            );
    }
}
