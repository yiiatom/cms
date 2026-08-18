<?php

declare(strict_types=1);

namespace Atom\Web\Profile\ChangePassword;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\UserRepository;
use Atom\Security\PasswordHasherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private CurrentUser $currentUser,
        private FlashInterface $flash,
        private FormHydrator $formHydrator,
        private PasswordHasherInterface $passwordHasher,
        private ResponseFactoryInterface $responseFactory,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-cms');

        $this->breadcrumbsProvider->add(
            new Breadcrumb(
                label: $t->translate('Change Password'),
            ),
        );

        $form = new ChangePasswordForm();
        $form->setTranslator($t);

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->currentPassword) {
            $user = $this->currentUser->getIdentity()->getUser();
            if (!$user->validatePassword($form->currentPassword, $this->passwordHasher)) {
                $form->addError(
                    $t->translate('Incorrect password.'),
                    ['currentPassword'],
                );
            }
        }

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
            $this->userRepository->save($user);

            $this->flash->add(
                'success',
                $t->translate('Your password has been updated.'),
            );

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.dashboard'),
                );
        }

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/change-password', [
                't' => $t,
                'form' => $form,
            ]);
    }
}
