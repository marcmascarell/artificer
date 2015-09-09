<?php

use Mascame\Artificer\Http\Controllers\ModelController as ModelController;
use Mascame\Artificer\Http\Controllers\UserController as UserController;
use Mascame\Artificer\Http\Controllers\PageController as PageController;
use Mascame\Artificer\Http\Controllers\PluginController as PluginController;

/*
 * Events to inject plugins wont work because routes are loaded before plugins
 */

Route::pattern('new_id', '\d+');
Route::pattern('old_id', '\d+');
Route::pattern('id', '\d+');
Route::pattern('integer', '\d+');

//Route::pattern('hash', '[a-z0-9]+');
//Route::pattern('hex', '[a-f0-9]+');
//Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
//Route::pattern('base', '[a-zA-Z0-9]+');
Route::pattern('slug', '[a-z0-9_-]+');
Route::pattern('username', '[a-z0-9_-]{3,16}');

// this works
//Event::listen('artificer.routes.model', function() {
//    Route::get('moco', array('uses' => ModelController::class . '@getRelatedFieldOutput'));
//
//});

Route::group(array(
    'prefix' => LaravelLocalization::setLocale(),
    'before' => 'artificer-localization|LaravelLocalizationRedirectFilter'
),
    function () {
        Route::group(array('prefix' => \Mascame\Artificer\Options\AdminOption::get('route_prefix')), function () {
            Route::get('install', array('as' => 'admin.install', 'uses' => PageController::class . '@install'));
        });
    });

Route::group(array(
    'prefix' => LaravelLocalization::setLocale(),
    'before' => 'artificer-auth|artificer-localization|LaravelLocalizationRedirectFilter'
),
    function () {
        Route::group(array('prefix' => \Mascame\Artificer\Options\AdminOption::get('route_prefix')), function () {

            Route::get('/', array('as' => 'admin.home', 'uses' => PageController::class . '@home'));

            Route::group(array('prefix' => 'user'), function () {
                Route::get('login',
                    array('as' => 'admin.showlogin', 'uses' => UserController::class . '@showLogin'));
                Route::post('login',
                    array('as' => 'admin.login', 'uses' => UserController::class . '@login'))->before('csrf');
                Route::get('logout',
                    array('as' => 'admin.logout', 'uses' => UserController::class . '@logout'));
            });

            Route::group(array('prefix' => 'page'), function () {
                Route::get('plugins',
                    array('as' => 'admin.page.plugins', 'uses' => PluginController::class . '@plugins'));
                Route::get('plugin/{slug}/install', array(
                    'as' => 'admin.page.plugin.install',
                    'uses' => PluginController::class . '@installPlugin'
                ));
                Route::get('plugin/{slug}/uninstall', array(
                    'as' => 'admin.page.plugin.uninstall',
                    'uses' => PluginController::class . '@uninstallPlugin'
                ));
            });

            Route::group(array('prefix' => 'model'), function () {
                Route::get('{slug}',
                    array('as' => 'admin.model.all', 'uses' => ModelController::class . '@all'));
                Route::get('{slug}/create',
                    array('as' => 'admin.model.create', 'uses' => ModelController::class . '@create'));
                Route::post('{slug}/store',
                    array('as' => 'admin.model.store', 'uses' => ModelController::class . '@store'));
                Route::get('{slug}/filter',
                    array('as' => 'admin.model.filter', 'uses' => ModelController::class . '@filter'));
                Route::get('{slug}/{id}',
                    array('as' => 'admin.model.show', 'uses' => ModelController::class . '@show'));
                Route::get('{slug}/{id}/edit',
                    array('as' => 'admin.model.edit', 'uses' => ModelController::class . '@edit'));
                Route::get('{slug}/{id}/edit/{field}',
                    array('as' => 'admin.model.field.edit', 'uses' => ModelController::class . '@field'));
                Route::put('{slug}/{id}',
                    array('as' => 'admin.model.update', 'uses' => ModelController::class . '@update'));
                Route::delete('{slug}/{id}',
                    array('as' => 'admin.model.destroy', 'uses' => ModelController::class . '@destroy'));

                Route::get('{slug}/{id}/field/{name}', array(
                    'as' => 'admin.model.field',
                    'uses' => ModelController::class . '@getRelatedFieldOutput'
                ));

                Event::fire('artificer.routes.model');
                Route::post('{slug}/{id}/upload', array(
                    'as' => 'admin.model.upload',
                    'uses' => '\Mascame\Artificer\Plugins\Plupload\PluploadController@plupload'
                ));
            });

            //	Route::post('upload', array('as' => 'admin.model.upload', function()
            //	{
            //		return Plupload::receive('file', function ($file)
            //		{
            //			$file->move(public_path() . '/uploads/', $file->getClientOriginalName());
            //
            //			return 'ready';
            //		});
            //	}));

//			$plugins = Config::get('artificer::admin.plugins.installed');
//
//            if (is_array($plugins)) {
//                foreach ($plugins as $pluginNamespace) {
//                    $pluginName = explode('/', $pluginNamespace);
//                    $pluginName = end($pluginName);
//
//                    $plugin = Config::get('artificer::plugins/' . $pluginNamespace . '/' . $pluginName);
//
//                    if (isset($plugin['routes'])) {
//                        $plugin_routes = $plugin['routes'];
//                        $plugin_routes();
//                    }
//                }
//            }

            Route::group(array('prefix' => 'plugin'), function () {

            });
            Route::get('logs', array(
                'as' => 'artificer-logreader-plugin',
                'uses' => 'Rap2hpoutre\LaravelLogViewer\LogViewerController@index'
            ));


            $pluginRoutes = \Mascame\Artificer\Plugin\PluginManager::getRoutes();

            foreach ($pluginRoutes as $pluginNamespace => $closure) {
                $closure();
            }


        });
    });

//
//Route::get('test', function() {
//	return View::make('admin::themes.admin-lte-custom.index');
//});
//
//Route::get('lock', function() {
//	return View::make('admin::themes.admin-lte-custom.pages.examples.lockscreen');
//});

//Route::get('gestor1337', function () {
//	if (Auth::check()) {
//		return Redirect::route('admin.home');
//	}
//
//	return Redirect::route('admin.model.showlogin');
//});
