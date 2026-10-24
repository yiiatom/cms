<?php

declare(strict_types=1);

use Atom\Middleware\AccessControlMiddleware;
use Atom\Middleware\CorePipelineMiddleware;
use Atom\Middleware\LocaleMiddleware;
use Atom\Middleware\LoginThemeMiddleware;
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
        ->middleware(CorePipelineMiddleware::class)
        ->action(Atom\Web\Logout\Action::class)
        ->name('atom.logout'),

    Group::create('/cms')
        ->middleware(CorePipelineMiddleware::class)
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

            Route::get('/settings')
                ->action(Atom\Web\Setting\Index\Action::class)
                ->name('atom.setting.index'),

            Route::get('/settings/manage')
                ->action(Atom\Web\Setting\Manage\Index\Action::class)
                ->name('atom.setting.manage.index'),

            Route::methods([Method::GET, Method::POST], '/settings/manage/group/create')
                ->action(Atom\Web\Setting\Manage\Group\Create\Action::class)
                ->name('atom.setting.manage.group.create'),

            Route::methods([Method::GET, Method::POST], '/settings/manage/group/{uuid}/edit')
                ->action(Atom\Web\Setting\Manage\Group\Edit\Action::class)
                ->name('atom.setting.manage.group.edit'),

            Route::post('/settings/manage/group/{uuid}/delete')
                ->action(Atom\Web\Setting\Manage\Group\Delete\Action::class)
                ->name('atom.setting.manage.group.delete'),

            Route::methods([Method::GET, Method::POST], '/settings/manage/setting/create')
                ->action(Atom\Web\Setting\Manage\Setting\Create\Action::class)
                ->name('atom.setting.manage.setting.create'),

            Route::methods([Method::GET, Method::POST], '/settings/manage/setting/{uuid}/edit')
                ->action(Atom\Web\Setting\Manage\Setting\Edit\Action::class)
                ->name('atom.setting.manage.setting.edit'),

            Route::post('/settings/manage/setting/{uuid}/delete')
                ->action(Atom\Web\Setting\Manage\Setting\Delete\Action::class)
                ->name('atom.setting.manage.setting.delete'),

            Route::get('/translit')
                ->action(Atom\Web\Translit\Action::class)
                ->name('atom.translit'),

            Route::get('/locale')
                ->action(Atom\Web\Locale\Action::class)
                ->name('atom.locale'),
        ),
];
