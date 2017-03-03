<?php

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
//    Route::get('moco', array('uses' => 'Mascame\Artificer\ModelController@getRelatedFieldOutput'));
//
//});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'before' => 'artificer-localization|LaravelLocalizationRedirectFilter', ],
    function () {
        Route::group(['prefix' => 'admin'], function () {
            Route::get('install', ['as' => 'admin.install', 'uses' => 'Mascame\Artificer\PageController@install']);
        });
    });

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'before' => 'artificer-auth|artificer-localization|LaravelLocalizationRedirectFilter', ],
    function () {
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/', ['as' => 'admin.home', 'uses' => 'Mascame\Artificer\PageController@home']);

            Route::group(['prefix' => 'user'], function () {
                Route::get('login', ['as' => 'admin.showlogin', 'uses' => 'Mascame\Artificer\UserController@showLogin']);
                Route::post('login', ['as' => 'admin.login', 'uses' => 'Mascame\Artificer\UserController@login'])->before('csrf');
                Route::get('logout', ['as' => 'admin.logout', 'uses' => 'Mascame\Artificer\UserController@logout']);
            });

            Route::group(['prefix' => 'page'], function () {
                Route::get('plugins', ['as' => 'admin.page.plugins', 'uses' => 'Mascame\Artificer\PluginController@plugins']);
                Route::get('plugin/{slug}/install', ['as' => 'admin.page.plugin.install', 'uses' => 'Mascame\Artificer\PluginController@installPlugin']);
                Route::get('plugin/{slug}/uninstall', ['as' => 'admin.page.plugin.uninstall', 'uses' => 'Mascame\Artificer\PluginController@uninstallPlugin']);
            });

            Route::group(['prefix' => 'model'], function () {
                Route::get('{slug}', ['as' => 'admin.model.all', 'uses' => 'Mascame\Artificer\ModelController@all']);
                Route::get('{slug}/create', ['as' => 'admin.model.create', 'uses' => 'Mascame\Artificer\ModelController@create']);
                Route::post('{slug}/store', ['as' => 'admin.model.store', 'uses' => 'Mascame\Artificer\ModelController@store']);
                Route::get('{slug}/filter', ['as' => 'admin.model.filter', 'uses' => 'Mascame\Artificer\ModelController@filter']);
                Route::get('{slug}/{id}', ['as' => 'admin.model.show', 'uses' => 'Mascame\Artificer\ModelController@show']);
                Route::get('{slug}/{id}/edit', ['as' => 'admin.model.edit', 'uses' => 'Mascame\Artificer\ModelController@edit']);
                Route::get('{slug}/{id}/edit/{field}', ['as' => 'admin.model.field.edit', 'uses' => 'Mascame\Artificer\ModelController@field']);
                Route::put('{slug}/{id}', ['as' => 'admin.model.update', 'uses' => 'Mascame\Artificer\ModelController@update']);
                Route::delete('{slug}/{id}', ['as' => 'admin.model.destroy', 'uses' => 'Mascame\Artificer\ModelController@destroy']);

                Route::get('{slug}/{id}/field/{name}', ['as' => 'admin.model.field', 'uses' => 'Mascame\Artificer\ModelController@getRelatedFieldOutput']);

                Event::fire('artificer.routes.model');
                Route::post('{slug}/{id}/upload', ['as' => 'admin.model.upload', 'uses' => 'Mascame\Artificer\Plugins\Plupload\PluploadController@plupload']);
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

            Route::group(['prefix' => 'plugin'], function () {
            });
            Route::get('logs', ['as' => 'artificer-logreader-plugin', 'uses' => 'Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);

            $plugin_routes = \Mascame\Artificer\Plugin\PluginManager::getRoutes();

            foreach ($plugin_routes as $pluginNamespace => $closure) {
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
