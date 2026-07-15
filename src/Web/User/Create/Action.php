<?php

declare(strict_types=1);

namespace Atom\Web\User\Create;

use Atom\Entity\User;
use Atom\Entity\UserRole;
use Atom\Entity\UserStatus;
use Atom\Repository\UserRepository;
use Atom\Web\Shared\Breadcrumbs\BreadcrumbsProvider;
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
        private BreadcrumbsProvider $breadcrumbsProvider,
        private FlashInterface $flash,
        private FormHydrator $formHydrator,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $this->breadcrumbsProvider
            ->add('Users', 'atom.user.index')
            ->add('Create User');

        $form = new UserCreateForm();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            if ($this->userRepository->findOneByUsername($form->username)) {
                $form->addError('Username is already in use.', ['username']);
            }
        }

        if ($form->isValid() && $form->email) {
            if ($this->userRepository->findOneByEmail($form->email)) {
                $form->addError('Email is already in use.', ['email']);
            }
        }

        if ($form->isValid()) {
            $user = User::create(
                username: $form->username,
                email: $form->email,
                status: UserStatus::from($form->status),
                role: UserRole::from($form->role),
                firstName: $form->firstName,
                lastName: $form->lastName,
            );
            $this->userRepository->save($user);

            $this->flash->add('success', 'User has been created.');

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.user.index'),
                );
        }


        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/create', [
                'form' => $form,
            ]);
    }
}
