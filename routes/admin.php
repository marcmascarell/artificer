<?php

use Mascame\Artificer\Http\Controllers\ModelController as ModelController;
use Mascame\Artificer\Http\Controllers\UserController as UserController;
use Mascame\Artificer\Http\Controllers\PageController as PageController;
use Mascame\Artificer\Http\Controllers\ExtensionController as ExtensionController;
use Mascame\Artificer\Http\Controllers\Auth\AuthController as AuthController;

//$ret = event('test');
//dd($ret);
/*
 * Events to inject plugins wont work because routes are loaded before plugins
 */

Route::pattern('new_id', '\d+');
Route::pattern('old_id', '\d+');
Route::pattern('id', '\d+');
Route::pattern('integer', '\d+');

Route::pattern('slug', '[a-z0-9_-]+');
Route::pattern('username', '[a-z0-9_-]{3,16}');

// this works
//Event::listen('artificer.routes.model', function() {
//    Route::get('moco', ['uses' => ModelController::class . '@getRelatedFieldOutput'));
//
//});

Route::group([
//    'prefix' => Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
//    'before' => 'artificer-localization|LaravelLocalizationRedirectFilter'
],
    function () {
        Route::group(['prefix' => \Mascame\Artificer\Options\AdminOption::get('routePrefix')], function () {
            Route::get('install', PageController::class . '@install')->name('admin.install');
        });
    }
);

Route::group([
    'middleware' => ['web'],
    'prefix' => \Mascame\Artificer\Options\AdminOption::get('routePrefix'),

//    'prefix' => Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
//    'before' => 'artificer-auth|artificer-localization|LaravelLocalizationRedirectFilter'
],
    function () {

        Route::group(['prefix' => 'user'], function () {
            Route::get('login', AuthController::class . '@showLoginForm')->name('admin.showlogin');
            Route::post('login', AuthController::class . '@login')->name('admin.login');
            Route::get('logout', AuthController::class . '@logout')->name('admin.logout');
        });

        Route::group([
            'middleware' => ['artificer-auth']
        ], function () {
            Route::get('test', function() {
                die('yes');
            });
        });

        Route::group([
            'middleware' => ['artificer-auth']
        ], function () {

            Route::get('/', ['as' => 'admin.home', 'uses' => PageController::class . '@home']);

            Route::get('extensions', ExtensionController::class . '@extensions')->name('admin.extensions');

            foreach (['plugins', 'widgets'] as $extensionType) {

                Route::group(['prefix' => $extensionType], function () use ($extensionType) {
                    Route::get('{slug}/install', ExtensionController::class . '@install')->name('admin.'. $extensionType .'.install');
                    Route::get('{slug}/uninstall', ExtensionController::class . '@uninstall')->name('admin.'. $extensionType .'.uninstall');
                });
            }

            Route::group(['prefix' => 'model'], function () {
                Route::get('{slug}', ModelController::class . '@all')->name('admin.model.all');
                Route::get('{slug}/create', ModelController::class . '@create')->name('admin.model.create');
                Route::post('{slug}/store', ModelController::class . '@store')->name('admin.model.store');
                Route::get('{slug}/filter', ModelController::class . '@filter')->name('admin.model.filter');
                Route::get('{slug}/{id}', ModelController::class . '@show')->name('admin.model.show');
                Route::get('{slug}/{id}/edit', ModelController::class . '@edit')->name('admin.model.edit');
                Route::get('{slug}/{id}/edit/{field}', ModelController::class . '@field')->name('admin.model.field.edit');
                Route::put('{slug}/{id}', ModelController::class . '@update')->name('admin.model.update');
                Route::delete('{slug}/{id}', ModelController::class . '@destroy')->name('admin.model.destroy');

                Route::get('{slug}/{id}/field/{name}', ModelController::class . '@getRelatedFieldOutput')->name('admin.model.field');

                Event::fire('artificer.routes.model');

//                Route::post('{slug}/{id}/upload', [
//                    'as' => 'admin.model.upload',
//                    'uses' => '\Mascame\Artificer\Plugins\Plupload\PluploadController@plupload'
//                ]);
            });

            Route::group(['prefix' => 'plugin'], function () {
                \Mascame\Artificer\Artificer::pluginManager()->outputRoutes();
            });
        });
    }
);