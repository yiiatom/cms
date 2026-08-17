<?php

declare(strict_types=1);

namespace Atom\Middleware;

use Atom\Helper\AuthRedirect;
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
use Yiisoft\Session\SessionInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;

final readonly class Authentication implements MiddlewareInterface
{
    public function __construct(
        private AuthRedirect $authRedirect,
        private CurrentRoute $currentRoute,
        private CurrentUser $currentUser,
        private FlashInterface $flash,
        private ResponseFactoryInterface $responseFactory,
        private SessionInterface $session,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $translator = $this->translator->withDefaultCategory('atom-cms');

        if ($this->currentUser->isGuest()) {
            if ($request->getMethod() === 'GET' && !$this->isAjax($request)) {
                $target = (string) $request->getUri();
                $this->authRedirect->setTargetUrl($target);
            }

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.login'),
                );
        }

        if (!in_array($this->currentRoute->getName(), [
            'atom.logout',
            'atom.profile.change-password',
        ])) {
            $user = $this->currentUser->getIdentity()->getUser();
            if ($user->isPasswordExpired()) {
                $this->flash->add(
                    'warning',
                    $translator->translate('Your password has expired. Please create a new one to continue.'),
                );
                return $this->responseFactory
                    ->createResponse(Status::FOUND)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('atom.profile.change-password'),
                    );
            }
        }

        return $handler->handle($request);
    }

    private function isAjax(ServerRequestInterface $request): bool
    {
        return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }
}
