<?php

declare(strict_types=1);

use Atom\Middleware\AccessControl;
use Atom\Middleware\Authentication;
use Atom\Middleware\LoginTheme;
use Atom\Middleware\MainTheme;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\User\Login\Cookie\CookieLoginMiddleware;

return [
    Group::create('/cms')
        ->middleware(CookieLoginMiddleware::class)
        ->routes(
            Route::methods([Method::GET, Method::POST], '/login')
                ->middleware(LoginTheme::class)
                ->action(Atom\Web\Login\Action::class)
                ->name('atom.login'),

            Route::get('/logout')
                ->middleware(Authentication::class)
                ->action(Atom\Web\Logout\Action::class)
                ->name('atom.logout'),

            Group::create('')
                ->middleware(MainTheme::class)
                ->middleware(Authentication::class)
                ->middleware(AccessControl::class)
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
                    ),
        ),
];
