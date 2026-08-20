<?php

declare(strict_types=1);

namespace Atom\Web\User\Create;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Entity\User;
use Atom\Entity\UserRole;
use Atom\Entity\UserStatus;
use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
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
                label: $t->translate('Add User'),
            ),
        );

        $form = (new UserCreateForm())->withTranslator($t);

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            if ($this->userRepository->findOneByUsername($form->username)) {
                $form->addError(
                    $t->translate('Username is already in use.'),
                    ['username'],
                );
            }
        }

        if ($form->isValid() && $form->email) {
            if ($this->userRepository->findOneByEmail($form->email)) {
                $form->addError(
                    $t->translate('Email is already in use.'),
                    ['email'],
                );
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

            $this->flash->add(
                'success',
                $t->translate('User has been created.'),
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
            ->render(__DIR__ . '/create', [
                't' => $t,
                'form' => $form,
            ]);
    }
}
