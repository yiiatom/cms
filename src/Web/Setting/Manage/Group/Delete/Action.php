<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Group\Delete;

use Atom\Repository\SettingGroupRepository;
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

        $this->settingGroupRepository->delete($group);

        $this->flash->add('success', $t->translate('Setting group has been deleted.'));

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(
                'Location', 
                $this->urlGenerator->generate('atom.setting.manage.index'),
            );
    }
}
