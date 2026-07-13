<?php

declare(strict_types=1);

namespace Atom\Web\Profile\ChangePassword;

use Atom\Repository\UserRepository;
use Atom\Security\PasswordHasherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private FlashInterface $flash,
        private FormHydrator $formHydrator,
        private PasswordHasherInterface $passwordHasher,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $form = new ChangePasswordForm();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->oldPassword) {
            $user = $this->currentUser->getIdentity()->getUser();
            if (!$user->validatePassword($form->oldPassword, $this->passwordHasher)) {
                $form->addError('Incorrect password.', ['oldPassword']);
            }
        }

        if ($form->isValid()) {
            $user->changePassword($form->newPassword, $this->passwordHasher);
            $this->userRepository->save($user);

            $this->flash->add('success', 'Your password has been updated.');

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.dashboard'),
                );
        }

        return $this->viewRenderer
            ->withLayout('@atom/src/Web/Shared/Layout/Main/main')
            ->render(__DIR__ . '/change-password', [
                'form' => $form,
            ]);
    }
}
