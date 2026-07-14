<?php

declare(strict_types=1);

namespace Atom\Web\User\Edit;

use Atom\Entity\UserRole;
use Atom\Entity\UserStatus;
use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private FlashInterface $flash,
        private FormHydrator $formHydrator,
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

        $form = new UserEditForm();
        $form->username = $user->getUsername();
        $form->email = $user->getEmail();
        $form->status = $user->getStatus()->value;
        $form->role = $user->getRole()->value;
        $form->firstName = $user->getFirstName();
        $form->lastName = $user->getLastName();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid() && $form->email) {
            $existingUser = $this->userRepository->findOneByEmail($form->email);
            if ($existingUser && $existingUser->getUuid() !== $user->getUuid()) {
                $form->addError('Email is already in use.', ['email']);
            }
        }

        if ($form->isValid()) {
            $user
                ->setEmail($form->email)
                ->setStatus(UserStatus::from($form->status))
                ->setRole(UserRole::from($form->role))
                ->setFirstName($form->firstName)
                ->setLastName($form->lastName);

            $this->userRepository->save($user);

            $this->flash->add('success', 'User has been updated.');

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.user.index'),
                );
        }

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/edit', [
                'form' => $form,
            ]);
    }
}
