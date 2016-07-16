<?php

use Mascame\Artificer\Http\Controllers\ModelController as ModelController;
use Mascame\Artificer\Http\Controllers\UserController as UserController;
use Mascame\Artificer\Http\Controllers\PageController as PageController;
use Mascame\Artificer\Http\Controllers\ExtensionController as ExtensionController;

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
            Route::get('install', ['as' => 'admin.install', 'uses' => PageController::class . '@install']);
        });
    });

Route::group([
    'middleware' => 'web'
//    'prefix' => Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
//    'before' => 'artificer-auth|artificer-localization|LaravelLocalizationRedirectFilter'
],
    function () {
        Route::group(['prefix' => \Mascame\Artificer\Options\AdminOption::get('routePrefix')], function () {

            Route::get('/', ['as' => 'admin.home', 'uses' => PageController::class . '@home']);

            Route::group(['prefix' => 'user'], function () {
                Route::get('login',
                    ['as' => 'admin.showlogin', 'uses' => UserController::class . '@showLogin']);
                Route::post('login',
                    ['as' => 'admin.login', 'uses' => UserController::class . '@login']); // ->before('csrf')
                Route::get('logout',
                    ['as' => 'admin.logout', 'uses' => UserController::class . '@logout']);
            });

            foreach (['plugins', 'widgets'] as $extensionType) {
                Route::group(['prefix' => $extensionType], function () use ($extensionType) {
                    Route::get('', [
                        'as' => 'admin.' . $extensionType,
                        'uses' => ExtensionController::class . '@extensions'
                    ]);

                    Route::get('{slug}/install', [
                        'as' => 'admin.'. $extensionType .'.install',
                        'uses' => ExtensionController::class . '@install'
                    ]);

                    Route::get('{slug}/uninstall', [
                        'as' => 'admin.'. $extensionType .'.uninstall',
                        'uses' => ExtensionController::class . '@uninstall'
                    ]);
                });    
            }

            Route::group(['prefix' => 'model'], function () {
                Route::get('{slug}',
                    ['as' => 'admin.model.all', 'uses' => ModelController::class . '@all']);
                Route::get('{slug}/create',
                    ['as' => 'admin.model.create', 'uses' => ModelController::class . '@create']);
                Route::post('{slug}/store',
                    ['as' => 'admin.model.store', 'uses' => ModelController::class . '@store']);
                Route::get('{slug}/filter',
                    ['as' => 'admin.model.filter', 'uses' => ModelController::class . '@filter']);
                Route::get('{slug}/{id}',
                    ['as' => 'admin.model.show', 'uses' => ModelController::class . '@show']);
                Route::get('{slug}/{id}/edit',
                    ['as' => 'admin.model.edit', 'uses' => ModelController::class . '@edit']);
                Route::get('{slug}/{id}/edit/{field}',
                    ['as' => 'admin.model.field.edit', 'uses' => ModelController::class . '@field']);
                Route::put('{slug}/{id}',
                    ['as' => 'admin.model.update', 'uses' => ModelController::class . '@update']);
                Route::delete('{slug}/{id}',
                    ['as' => 'admin.model.destroy', 'uses' => ModelController::class . '@destroy']);

                Route::get('{slug}/{id}/field/{name}', [
                    'as' => 'admin.model.field',
                    'uses' => ModelController::class . '@getRelatedFieldOutput'
                ]);

                Event::fire('artificer.routes.model');

//                Route::post('{slug}/{id}/upload', [
//                    'as' => 'admin.model.upload',
//                    'uses' => '\Mascame\Artificer\Plugins\Plupload\PluploadController@plupload'
//                ]);
            });

            Route::group(['prefix' => 'plugin'], function () {
                \Mascame\Artificer\Artificer::pluginManager()->outputRoutes();
            });

//            Route::get('logs', [
//                'as' => 'artificer-logreader-plugin',
//                'uses' => 'Rap2hpoutre\LaravelLogViewer\LogViewerController@index'
//            ]);
            

        });
    });
