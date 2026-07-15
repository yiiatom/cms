<?php

declare(strict_types=1);

namespace Atom\Web\User\Password;

use Atom\Repository\UserRepository;
use Atom\Security\PasswordHasherInterface;
use Atom\Web\Shared\Breadcrumbs\BreadcrumbsProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private BreadcrumbsProvider $breadcrumbsProvider,
        private FlashInterface $flash,
        private FormHydrator $formHydrator,
        private PasswordHasherInterface $passwordHasher,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        #[RouteArgument('uuid')] string $uuid,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $this->breadcrumbsProvider
            ->add('Users', 'atom.user.index')
            ->add('Change User Password');

        $user = $this->userRepository->findOneByUuid($uuid);

        if (!$user) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        if ($user->isSuperAdmin()) {
            return $this->responseFactory
                ->createResponse(Status::FORBIDDEN);
        }

        $form = new UserPasswordForm();
        $form->username = $user->getUsername();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            $user->changePassword($form->newPassword, $this->passwordHasher);
            if ($form->requirePasswordChange) {
                $user->forcePasswordChange();
            }

            $this->userRepository->save($user);

            $this->flash->add('success', 'User password has been updated.');

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.user.index'),
                );
        }

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/password', [
                'form' => $form,
            ]);
    }
}
