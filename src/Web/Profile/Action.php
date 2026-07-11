<?php

declare(strict_types=1);

namespace Atom\Web\Profile;

use Atom\Repository\UserRepository;
use Atom\Web\Profile\ProfileForm;
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
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $identity = $this->currentUser->getIdentity();

        $form = new ProfileForm();
        $form->username = $identity->username;
        $form->email = $identity->email;
        $form->firstName = $identity->firstName;
        $form->lastName = $identity->lastName;

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            $identity->email = $form->email;
            $identity->firstName = $form->firstName;
            $identity->lastName = $form->lastName;
            $this->userRepository->save($identity);

            $this->flash->add('success', 'Your profile has been updated.');

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.dashboard'),
                );
        }

        return $this->viewRenderer
            ->withLayout('@atom/src/Web/Shared/Layout/Main/layout')
            ->render(__DIR__ . '/template', [
                'form' => $form,
            ]);
    }
}
