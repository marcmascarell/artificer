<?php

use \Mascame\Artificer\Options\AdminOption;

Route::filter('artificer-auth', function () {
    $roles = AdminOption::get('auth.roles');
    $role_column = AdminOption::get('auth.role_column');

    if (Auth::guest()
        && Route::currentRouteName() != 'admin.showlogin'
        && Route::currentRouteName() != 'admin.login'
    ) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::route('admin.showlogin');
        }
    } elseif (Auth::check()
        && Route::currentRouteName() != 'admin.logout') {
        if (! in_array(Auth::user()->$role_column, $roles)) {
            return Redirect::route('admin.logout');
        }
    }
});

Route::filter('artificer-localization', function () {
    $langs = AdminOption::get('localization.user_locales');

    if (! in_array(LaravelLocalization::getCurrentLocale(), $langs)) {
        LaravelLocalization::setLocale(array_keys($langs)[0]);
    }

    LaravelLocalization::setSupportedLocales($langs);
});
