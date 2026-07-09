<?php

declare(strict_types=1);

use Atom\Web\Shared\Middleware\Authentication;
use Atom\Web\Shared\Middleware\LoginTheme;
use Atom\Web\Shared\Middleware\MainTheme;
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

                    Route::methods([Method::GET, Method::POST], '/profile')
                        ->action(Atom\Web\Profile\Action::class)
                        ->name('atom.profile'),

                    Route::methods([Method::GET, Method::POST], '/change-password')
                        ->action(Atom\Web\ChangePassword\Action::class)
                        ->name('atom.change-password'),

                    Route::get('/user/list')
                        ->action(Atom\Web\User\List\Action::class)
                        ->name('atom.user.list'),

                    Route::get('/user/create')
                        ->action(Atom\Web\User\Сreate\Action::class)
                        ->name('atom.user.create'),
                ),
        ),
];
