<?php

use \Mascame\Artificer\Options\AdminOption;
use \Mascame\Artificer\Options\ModelOption;

Route::filter('artificer-auth', function () {
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
    }
});

Route::filter('artificer-permission', function () {

	$role = AdminOption::get('auth.role_column');

	dd(\Mascame\Artificer\Model::$current);

	$permissions = ModelOption::get('permissions');

	dd($permissions);

	if (Auth::user()->$role)
	{
		dd('yeah');
	} else {

	}
});
