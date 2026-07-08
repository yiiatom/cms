<?php

declare(strict_types=1);

namespace Atom\Web\Login;

use Atom\User\Entity\User;
use Atom\User\Data\UserRepository;
use Atom\User\Service\UserService;
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
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
        private UserService $userService,
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

        $identity = null;
        $form = new LoginForm();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->username && $form->password) {
            $identity = $this->userRepository->findOneByUsername($form->username);
            if (!$identity || $identity->status !== User::STATUS_ACTIVE || !$this->userService->validatePassword($identity, $form->password)) {
                $form->addError('Incorrect username or password.', ['password']);
            }
        }

        if ($form->isValid()) {
            $this->currentUser->login($identity);

            $response = $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.dashboard'),
                );

            if ($form->rememberMe) {
                if (!$identity->authKey) {
                    $identity->authKey = Uuid::uuid7()->toString();
                    $this->userRepository->save($identity);
                }
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
