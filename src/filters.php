<?php

use \Mascame\Artificer\Options\AdminOption;
use \Mascame\Artificer\Options\ModelOption;

Route::filter('artificer-auth', function () {
    $roles = AdminOption::get('auth.roles');
    $role_column = AdminOption::get('auth.role_column');

    if (Auth::guest()
        && Route::currentRouteName() != 'admin.showlogin'
        && Route::currentRouteName() != 'admin.login'
    )
    {
        if (Request::ajax())
        {
            return Response::make('Unauthorized', 401);
        }
        else
        {
            return Redirect::route('admin.showlogin');
        }
    } else if (Auth::check()
        && Route::currentRouteName() != 'admin.logout') {
        if (!in_array(Auth::user()->$role_column, $roles)) {
            return Redirect::route('admin.logout');
        }
    }
});
