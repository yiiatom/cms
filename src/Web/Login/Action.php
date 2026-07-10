<?php

declare(strict_types=1);

namespace Atom\Web\Login;

use Atom\Data\UserAuthKeyRepository;
use Atom\Data\UserRepository;
use Atom\Entity\User;
use Atom\Entity\UserAuthKey;
use Atom\Identity\UserIdentity;
use Atom\Security\PasswordHasherInterface;
use Atom\Web\Login\LoginForm;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\User\CurrentUser;
use Yiisoft\User\Login\Cookie\CookieLogin;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private FormHydrator $formHydrator,
        private PasswordHasherInterface $passwordHasher,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserAuthKeyRepository $userAuthKeyRepository,
        private UserRepository $userRepository,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        CookieLogin $cookieLogin,
    ): ResponseInterface
    {
        if (!$this->currentUser->isGuest()) {
            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.dashboard'),
                );
        }

        $user = null;
        $form = new LoginForm();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->username && $form->password) {
            $user = $this->userRepository->findOneByUsername($form->username);
            if (!$user || $user->status !== User::STATUS_ACTIVE || !$user->validatePassword($form->password, $this->passwordHasher)) {
                $form->addError('Incorrect username or password.', ['password']);
            }
        }

        if ($form->isValid()) {
            $identity = new UserIdentity($user, $this->userAuthKeyRepository);
            $this->currentUser->login($identity);

            $response = $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.dashboard'),
                );

            if ($form->rememberMe) {
                $userAuthKey = UserAuthKey::create($user->uuid);
                $this->userAuthKeyRepository->save($userAuthKey);
                $response = $cookieLogin->addCookie($identity, $response);
            }

            return $response;
        }

        return $this->viewRenderer
            ->withLayout('@atom/src/Web/Shared/Layout/Login/layout')
            ->render(__DIR__ . '/template', [
                'form' => $form,
            ]);
    }
}
