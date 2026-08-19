<?php

declare(strict_types=1);

namespace Atom\Web\User\Edit;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
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
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private FlashInterface $flash,
        private FormHydrator $formHydrator,
        private ResponseFactoryInterface $responseFactory,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        #[RouteArgument('uuid')] string $uuid,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-users');

        $user = $this->userRepository->findOneByUuid($uuid);

        if (!$user) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        if ($user->isSuperAdmin()) {
            return $this->responseFactory
                ->createResponse(Status::FORBIDDEN);
        }

        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: $t->translate('Users'),
                url: $this->urlGenerator->generate('atom.user.index'),
            ),
            new Breadcrumb(
                label: $user->getDisplayName(),
            ),
        );

        $form = new UserEditForm();
        $form->setTranslator($t);
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
                $form->addError(
                    $t->translate('Email is already in use.'),
                    ['email'],
                );
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

            $this->flash->add(
                'success',
                $t->translate('User has been updated.'),
            );

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
                't' => $t,
                'form' => $form,
                'user' => $user,
            ]);
    }
}
