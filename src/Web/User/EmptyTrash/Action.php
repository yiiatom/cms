<?php

declare(strict_types=1);

namespace Atom\Web\User\EmptyTrash;

use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Translator\TranslatorInterface;

final readonly class Action
{
    public function __construct(
        private FlashInterface $flash,
        private ResponseFactoryInterface $responseFactory,
        private TranslatorInterface $translator,
        private UserRepository $userRepository,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-users');

        $count = $this->userRepository->purgeDeleted();

        if ($count === 0) {
            $this->flash->add(
                'success',
                $t->translate('Trash is empty.'),
            );
        } else {
            $this->flash->add(
                'success',
                $t->translate('Trash has been cleared.'),
            );
        }

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(
                'Location', 
                $this->urlGenerator->generate('atom.user.index'),
            );
    }
}
