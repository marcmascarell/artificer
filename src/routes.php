<?php


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

Route::group(array(
	'prefix' => LaravelLocalization::setLocale(),
	'before' => 'LaravelLocalizationRedirectFilter|artificer-auth'),
	function () {
		Route::group(array('prefix' => 'admin'), function () {

			Route::get('/', array('as' => 'admin.home', 'uses' => 'Mascame\Artificer\PageController@home'));

			Route::group(array('prefix' => 'user'), function () {
				Route::get('login', array('as' => 'admin.showlogin', 'uses' => 'Mascame\Artificer\UserController@showLogin'));
				Route::post('login', array('as' => 'admin.login', 'uses' => 'Mascame\Artificer\UserController@login'))->before('csrf');
				Route::get('logout', array('as' => 'admin.logout', 'uses' => 'Mascame\Artificer\UserController@logout'));
			});

			Route::group(array('prefix' => 'page'), function () {
				Route::get('plugins', array('as' => 'admin.page.plugins', 'uses' => 'Mascame\Artificer\PageController@plugins'));
				Route::get('plugin/{slug}/install', array('as' => 'admin.page.plugin.install', 'uses' => 'Mascame\Artificer\PageController@installPlugin'));
				Route::get('plugin/{slug}/uninstall', array('as' => 'admin.page.plugin.uninstall', 'uses' => 'Mascame\Artificer\PageController@uninstallPlugin'));
			});

			Route::group(array('prefix' => 'model'), function () {
				Route::get('{slug}', array('as' => 'admin.all', 'uses' => 'Mascame\Artificer\ModelController@all'));
				Route::get('{slug}/create', array('as' => 'admin.create', 'uses' => 'Mascame\Artificer\ModelController@create'));
				Route::post('{slug}/store', array('as' => 'admin.store', 'uses' => 'Mascame\Artificer\ModelController@store'));
				Route::get('{slug}/{id}', array('as' => 'admin.show', 'uses' => 'Mascame\Artificer\ModelController@show'));
				Route::get('{slug}/{id}/edit', array('as' => 'admin.edit', 'uses' => 'Mascame\Artificer\ModelController@edit'));
				Route::put('{slug}/{id}', array('as' => 'admin.update', 'uses' => 'Mascame\Artificer\ModelController@update'));
				Route::delete('{slug}/{id}', array('as' => 'admin.destroy', 'uses' => 'Mascame\Artificer\ModelController@destroy'));

				Route::get('{slug}/{id}/field/{name}', array('as' => 'admin.field', 'uses' => 'Mascame\Artificer\ModelController@getRelatedFieldOutput'));

				Event::fire('artificer.routes.model');
				Route::post('{slug}/{id}/upload', array('as' => 'admin.upload', 'uses' => 'Mascame\Artificer\Plugins\Plupload\PluploadController@plupload'));
			});

			//	Route::post('upload', array('as' => 'admin.upload', function()
			//	{
			//		return Plupload::receive('file', function ($file)
			//		{
			//			$file->move(public_path() . '/uploads/', $file->getClientOriginalName());
			//
			//			return 'ready';
			//		});
			//	}));

			$plugins = Config::get('artificer::admin.plugins.installed');

			foreach ($plugins as $pluginNamespace) {
				$pluginName = explode('/', $pluginNamespace);
				$pluginName = end($pluginName);

				$plugin = Config::get('artificer::plugins/' . $pluginNamespace . '/' . $pluginName);

				if (isset($plugin['routes'])) {
					$plugin_routes = $plugin['routes'];
					$plugin_routes();
				}
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

Route::get('gestor1337', function () {
	if (Auth::check()) {
		return Redirect::route('admin.home');
	}

	return Redirect::route('admin.showlogin');
});
