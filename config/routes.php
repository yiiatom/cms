<?php

declare(strict_types=1);

use Atom\Middleware\AccessControlMiddleware;
use Atom\Middleware\LocaleMiddleware;
use Atom\Middleware\LoginThemeMiddleware;
use Atom\Middleware\PipelineMiddleware;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\User\Login\Cookie\CookieLoginMiddleware;

return [
    Route::methods([Method::GET, Method::POST], '/cms/login')
        ->middleware(LoginThemeMiddleware::class)
        ->middleware(LocaleMiddleware::class)
        ->middleware(CookieLoginMiddleware::class)
        ->action(Atom\Web\Login\Action::class)
        ->name('atom.login'),

    Route::get('/cms/logout')
        ->middleware(PipelineMiddleware::class)
        ->action(Atom\Web\Logout\Action::class)
        ->name('atom.logout'),

    Group::create('/cms')
        ->middleware(PipelineMiddleware::class)
        ->middleware(AccessControlMiddleware::class)
        ->routes(
            Route::get('')
                ->action(Atom\Web\Dashboard\Action::class)
                ->name('atom.dashboard'),

            Route::methods([Method::GET, Method::POST], '/profile/edit')
                ->action(Atom\Web\Profile\Edit\Action::class)
                ->name('atom.profile.edit'),

            Route::methods([Method::GET, Method::POST], '/profile/change-password')
                ->action(Atom\Web\Profile\ChangePassword\Action::class)
                ->name('atom.profile.change-password'),

            Route::get('/users')
                ->action(Atom\Web\User\Index\Action::class)
                ->name('atom.user.index'),

            Route::methods([Method::GET, Method::POST], '/users/create')
                ->action(Atom\Web\User\Create\Action::class)
                ->name('atom.user.create'),

            Route::methods([Method::GET, Method::POST], '/users/{uuid}/edit')
                ->action(Atom\Web\User\Edit\Action::class)
                ->name('atom.user.edit'),

            Route::post('/users/{uuid}/delete')
                ->action(Atom\Web\User\Delete\Action::class)
                ->name('atom.user.delete'),

            Route::methods([Method::GET, Method::POST], '/users/{uuid}/password')
                ->action(Atom\Web\User\Password\Action::class)
                ->name('atom.user.password'),

            Route::get('/users/trash')
                ->action(Atom\Web\User\Trash\Action::class)
                ->name('atom.user.trash'),

            Route::post('/users/{uuid}/restore')
                ->action(Atom\Web\User\Restore\Action::class)
                ->name('atom.user.restore'),

            Route::post('/users/empty-trash')
                ->action(Atom\Web\User\EmptyTrash\Action::class)
                ->name('atom.user.empty-trash'),

            Route::get('/translit')
                ->action(Atom\Web\Translit\Action::class)
                ->name('atom.translit'),

            Route::get('/locale')
                ->action(Atom\Web\Locale\Action::class)
                ->name('atom.locale'),
        ),
];
