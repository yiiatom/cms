<?php

declare(strict_types=1);

namespace Atom\Web\User\Delete;

use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;

final readonly class Action
{
    public function __construct(
        private FlashInterface $flash,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        #[RouteArgument('uuid')] string $uuid,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $user = $this->userRepository->findOneByUuid($uuid);

        if (!$user) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        if ($user->isSuperAdmin()) {
            return $this->responseFactory
                ->createResponse(Status::FORBIDDEN);
        }

        $user->delete();

        $this->userRepository->save($user);

        $this->flash->add('success', 'User has been deleted.');

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(
                'Location', 
                $this->urlGenerator->generate('atom.user.index'),
            );
    }
}
