<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Middleware;

use DateTimeImmutable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\User\CurrentUser;

final readonly class Authentication implements MiddlewareInterface
{
    public function __construct(
        private CurrentRoute $currentRoute,
        private CurrentUser $currentUser,
        private FlashInterface $flash,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if ($this->currentUser->isGuest()) {
            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.login'),
                );
        }

        if ($this->currentRoute->getName() !== 'atom.change-password') {
            $identity = $this->currentUser->getIdentity();
            $expired = $identity->passwordExpiresAt && ($identity->passwordExpiresAt < (new DateTimeImmutable));
            if ($expired) {
                $this->flash->add('warning', 'Your password has expired. Please create a new one to continue.');
                return $this->responseFactory
                    ->createResponse(Status::FOUND)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('atom.change-password'),
                    );
            }
        }

        return $handler->handle($request);
    }
}
