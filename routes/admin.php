<?php

use Mascame\Artificer\Controllers\ModelController as ModelController;
use Mascame\Artificer\Controllers\HomeController as HomeController;
use Mascame\Artificer\Controllers\InstallController as InstallController;
use Mascame\Artificer\Controllers\ExtensionController as ExtensionController;
use Mascame\Artificer\Controllers\LoginController as LoginController;
use Mascame\Artificer\Controllers\ForgotPasswordController as ForgotPasswordController;
use Mascame\Artificer\Controllers\RegisterController as RegisterController;
use Mascame\Artificer\Controllers\ResetPasswordController as ResetPasswordController;

Route::pattern('new_id', '\d+');
Route::pattern('old_id', '\d+');
Route::pattern('id', '\d+');
Route::pattern('integer', '\d+');

Route::pattern('slug', '[a-z0-9_-]+');
Route::pattern('username', '[a-z0-9_-]{3,16}');


Route::group([],
    function () {
        Route::group(['prefix' => \Mascame\Artificer\Options\AdminOption::get('route_prefix')], function () {
            Route::get('install', InstallController::class . '@install')->name('admin.install');
        });
    }
);

Route::group([
    'middleware' => ['web'],
    'prefix' => \Mascame\Artificer\Options\AdminOption::get('route_prefix'),
],
    function () {

        Route::group(['prefix' => 'user'], function () {
            // Authentication Routes...
            Route::get('login', LoginController::class . '@showLoginForm')->name('admin.login.show');
            Route::post('login', LoginController::class . '@login')->name('admin.login');
            Route::get('logout', LoginController::class . '@logout')->name('admin.logout');

            // Registration Routes...
            Route::get('register', RegisterController::class . '@showRegistrationForm')->name('admin.register.show');
            Route::post('register', RegisterController::class . '@register')->name('admin.register');

            // Password Reset Routes...
            Route::get('password/reset', ForgotPasswordController::class . '@showLinkRequestForm')->name('admin.password.reset.show');
            Route::post('password/email', ForgotPasswordController::class . '@sendResetLinkEmail')->name('admin.password.reset.email');
            Route::get('password/reset/{token}', ResetPasswordController::class . '@showResetForm')->name('admin.password.reset.recover');
            Route::post('password/reset', ResetPasswordController::class . '@reset')->name('admin.password.reset');
        });

        Route::group(['middleware' => ['artificer-auth']], function () {

            Route::get('/', ['as' => 'admin.home', 'uses' => HomeController::class . '@home']);

            Route::get('extensions', ExtensionController::class . '@extensions')->name('admin.extensions');

            foreach (['plugins', 'widgets'] as $extensionType) {
                Route::group(['prefix' => $extensionType], function () use ($extensionType) {
                    Route::get('{slug}/install', ExtensionController::class . '@install')->name('admin.'. $extensionType .'.install');
                    Route::get('{slug}/uninstall', ExtensionController::class . '@uninstall')->name('admin.'. $extensionType .'.uninstall');
                });
            }

            Route::group(['prefix' => 'model'], function () {
                Route::get('{slug}', ModelController::class . '@all')->name('admin.model.all');
                Route::get('{slug}/filter', ModelController::class . '@filter')->name('admin.model.filter');
                Route::get('{slug}/create', ModelController::class . '@create')->name('admin.model.create');
                Route::get('{slug}/{id}', ModelController::class . '@show')->name('admin.model.show');
                Route::get('{slug}/{id}/edit', ModelController::class . '@edit')->name('admin.model.edit');
                Route::get('{slug}/{id}/edit/{field}', ModelController::class . '@field')->name('admin.model.field.edit');
                Route::post('{slug}/store', ModelController::class . '@updateOrCreate')->name('admin.model.store');
                Route::put('{slug}/{id}', ModelController::class . '@updateOrCreate')->name('admin.model.update');
                Route::delete('{slug}/{id}', ModelController::class . '@destroy')->name('admin.model.destroy');

                Route::get('{slug}/{id}/field/{name}', ModelController::class . '@getRelatedFieldOutput')->name('admin.model.field');
            });

            Route::group(['prefix' => 'plugin'], function () {
                \Mascame\Artificer\Artificer::pluginManager()->outputRoutes();
            });
        });
    }
);