<?php

declare(strict_types=1);

namespace Atom\Web\Login;

use Atom\Entity\User;
use Atom\Entity\UserAuthKey;
use Atom\Entity\UserStatus;
use Atom\Helper\AuthRedirect;
use Atom\Identity\UserIdentity;
use Atom\Repository\UserAuthKeyRepository;
use Atom\Repository\UserRepository;
use Atom\Security\PasswordHasherInterface;
use Atom\Web\Login\LoginForm;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\Session\SessionInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\User\Login\Cookie\CookieLogin;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private AuthRedirect $authRedirect,
        private CurrentUser $currentUser,
        private FormHydrator $formHydrator,
        private PasswordHasherInterface $passwordHasher,
        private ResponseFactoryInterface $responseFactory,
        private SessionInterface $session,
        private TranslatorInterface $translator,
        private UserAuthKeyRepository $userAuthKeyRepository,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        CookieLogin $cookieLogin,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        if (!$this->currentUser->isGuest()) {
            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    Header::LOCATION,
                    $this->authRedirect->getTargetUrl(),
                );
        }

        $t = $this->translator->withDefaultCategory('atom-cms');

        $user = null;
        $form = (new LoginForm())->withTranslator($t);

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->username && $form->password) {
            $user = $this->userRepository->findOneByUsername($form->username);
            if (!$user || $user->getStatus() !== UserStatus::ACTIVE || !$user->validatePassword($form->password, $this->passwordHasher)) {
                $form->addError(
                    $t->translate('Incorrect username or password.'),
                    ['password'],
                );
            }
        }

        if ($form->isValid()) {
            $identity = new UserIdentity($user, $this->userAuthKeyRepository);
            $this->currentUser->login($identity);

            $response = $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    Header::LOCATION,
                    $this->authRedirect->getTargetUrl(),
                );

            if ($form->rememberMe) {
                $userAuthKey = UserAuthKey::create($user->getUuid());
                $this->userAuthKeyRepository->save($userAuthKey);
                $response = $cookieLogin->addCookie($identity, $response);
            }

            return $response;
        }

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/login', [
                't' => $t,
                'form' => $form,
            ]);
    }
}
