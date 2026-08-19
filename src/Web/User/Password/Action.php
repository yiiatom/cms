<?php

declare(strict_types=1);

namespace Atom\Web\User\Password;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\UserRepository;
use Atom\Security\PasswordHasherInterface;
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
        private PasswordHasherInterface $passwordHasher,
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

        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: $t->translate('Users'),
                url: $this->urlGenerator->generate('atom.user.index'),
            ),
            new Breadcrumb(
                label: $t->translate('Change User Password'),
            ),
        );

        $user = $this->userRepository->findOneByUuid($uuid);

        if (!$user) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        if ($user->isSuperAdmin()) {
            return $this->responseFactory
                ->createResponse(Status::FORBIDDEN);
        }

        $form = new UserPasswordForm();
        $form->setTranslator($t);
        $form->username = $user->getUsername();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            if ($form->newPassword !== $form->confirmPassword) {
                $form->addError(
                    $t->translate('Passwords do not match.'),
                    ['confirmPassword'],
                );
            }
        }

        if ($form->isValid()) {
            $user->changePassword($form->newPassword, $this->passwordHasher);
            if ($form->requirePasswordChange) {
                $user->forcePasswordChange();
            }

            $this->userRepository->save($user);

            $this->flash->add(
                'success',
                $t->translate('User password has been updated.'),
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
            ->render(__DIR__ . '/password', [
                't' => $t,
                'form' => $form,
            ]);
    }
}
