<?php

declare(strict_types=1);

namespace Atom\Web\User\Create;

use DateTimeImmutable;
use Atom\Data\UserRepository;
use Atom\Entity\User;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
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
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $form = new UserForm();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            if ($this->userRepository->findOneByUsername($form->username)) {
                $form->addError('Username is already in use.', ['username']);
            }
        }

        if ($form->isValid()) {
            $user = User::create(
                username: $form->username,
                email: $form->email,
                firstName: $form->firstName,
                lastName: $form->lastName,
                createdAt: new DateTimeImmutable(),
            );
            $this->userRepository->save($user);

            $this->flash->add('success', 'User has been created.');

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.user.list'),
                );
        }


        return $this->viewRenderer
            ->withLayout('@atom/src/Web/Shared/Layout/Main/layout')
            ->render(__DIR__ . '/template', [
                'form' => $form,
            ]);
    }
}
