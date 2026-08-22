<?php

declare(strict_types=1);

namespace Atom\Web\Profile\Edit;

use Atom\Breadcrumbs\Breadcrumb;
use Atom\Breadcrumbs\BreadcrumbsProvider;
use Atom\Repository\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Header;
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
                label: $t->translate('Profile'),
            )
        );

        $user = $this->currentUser->getIdentity()->getUser();

        $form = (new ProfileForm())->withTranslator($t);
        $form->username = $user->getUsername();
        $form->email = $user->getEmail();
        $form->firstName = $user->getFirstName();
        $form->lastName = $user->getLastName();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            $user
                ->setEmail($form->email)
                ->setFirstName($form->firstName)
                ->setLastName($form->lastName);

            $this->userRepository->save($user);

            $this->flash->add(
                'success',
                $t->translate('Your profile has been updated.'),
            );

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    Header::LOCATION,
                    $this->urlGenerator->generate('atom.dashboard'),
                );
        }

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/edit', [
                't' => $t,
                'form' => $form,
            ]);
    }
}
