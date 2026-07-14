<?php

declare(strict_types=1);

namespace Atom\Middleware;

use Atom\Entity\UserRole;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Status;
use Yiisoft\User\CurrentUser;

final class AccessControl implements MiddlewareInterface
{
    public function __construct(
        private CurrentUser $currentUser,
        private ResponseFactoryInterface $responseFactory,
        private UserRole $role = UserRole::ADMIN,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface
    {
        $user = $this->currentUser->getIdentity()->getUser();

        if ($user === null || (!$user->canAccess($this->role))) {
            return $this->responseFactory
                ->createResponse(Status::FORBIDDEN);
        }

        return $handler->handle($request);
    }

    public function withRole(UserRole $role): self
    {
        $this->role = $role;

        return $this;
    }
}
