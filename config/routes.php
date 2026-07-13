<?php

declare(strict_types=1);

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

            Group::create('')
                ->middleware(MainTheme::class)
                ->middleware(Authentication::class)
                ->routes(
                    Route::get('')
                        ->action(Atom\Web\Dashboard\Action::class)
                        ->name('atom.dashboard'),

                    Route::get('/logout')
                        ->action(Atom\Web\Logout\Action::class)
                        ->name('atom.logout'),

                    Route::methods([Method::GET, Method::POST], '/profile/edit')
                        ->action(Atom\Web\Profile\Edit\Action::class)
                        ->name('atom.profile.edit'),

                    Route::methods([Method::GET, Method::POST], '/profile/change-password')
                        ->action(Atom\Web\Profile\ChangePassword\Action::class)
                        ->name('atom.profile.change-password'),

                    Route::get('/user/list')
                        ->action(Atom\Web\User\List\Action::class)
                        ->name('atom.user.list'),

                    Route::methods([Method::GET, Method::POST], '/user/create')
                        ->action(Atom\Web\User\Create\Action::class)
                        ->name('atom.user.create'),

                    Route::methods([Method::GET, Method::POST], '/user/edit/{username}')
                        ->action(Atom\Web\User\Edit\Action::class)
                        ->name('atom.user.edit'),

                    Route::get('/user/delete/{username}')
                        ->action(Atom\Web\User\Delete\Action::class)
                        ->name('atom.user.delete'),
                ),
        ),
];
