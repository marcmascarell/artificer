<?php

use Mascame\Artificer\Artificer;

Route::pattern('new_id', '\d+');
Route::pattern('old_id', '\d+');
Route::pattern('id', '\d+');
Route::pattern('integer', '\d+');

//Route::pattern('hash', '[a-z0-9]+');
//Route::pattern('hex', '[a-f0-9]+');
//Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
//Route::pattern('base', '[a-zA-Z0-9]+');
Route::pattern('slug', '[a-z0-9-]+');
Route::pattern('username', '[a-z0-9_-]{3,16}');

Route::group(array('prefix' => 'admin'), function () {

//    Route::get('user', function()
//    {
//        return Schema::getColumnListing('fake');
//    });
//    Route::get('index', function() {
//        new \Mascame\Artificer\Admin;
//    });
//    Route::get('index', array('uses' => 'Mascame\Artificer\ModelController@index'));

	Route::get('/', array('as' => 'admin.home', 'uses' => 'Mascame\Artificer\PageController@home'));

	Route::get('login', array('as' => 'admin.showlogin', 'uses' => 'Mascame\Artificer\UserController@showLogin'));
	Route::post('login', array('as' => 'admin.login', 'uses' => 'Mascame\Artificer\UserController@doLogin'))->before('csrf');
	Route::get('logout', array('as' => 'admin.logout', 'uses' => 'Mascame\Artificer\UserController@doLogout'));

	Route::get('page/plugins', array('as' => 'admin.page.plugins', 'uses' => 'Mascame\Artificer\PageController@plugins'));
	Route::get('page/plugin/{slug}/install', array('as' => 'admin.page.plugin.install', 'uses' => 'Mascame\Artificer\PageController@installPlugin'));
	Route::get('page/plugin/{slug}/uninstall', array('as' => 'admin.page.plugin.uninstall', 'uses' => 'Mascame\Artificer\PageController@uninstallPlugin'));

//	Model
	Route::get('model/{slug}', array('as' => 'admin.all', 'uses' => 'Mascame\Artificer\ModelController@all'));
	Route::get('model/{slug}/create', array('as' => 'admin.create', 'uses' => 'Mascame\Artificer\ModelController@create'));
	Route::post('model/{slug}/store', array('as' => 'admin.store', 'uses' => 'Mascame\Artificer\ModelController@store'))->before('csrf');
	Route::get('model/{slug}/{id}', array('as' => 'admin.show', 'uses' => 'Mascame\Artificer\ModelController@show'));
	Route::get('model/{slug}/{id}/edit', array('as' => 'admin.edit', 'uses' => 'Mascame\Artificer\ModelController@edit'));
	Route::put('model/{slug}/{id}', array('as' => 'admin.update', 'uses' => 'Mascame\Artificer\ModelController@update'))->before('csrf');
//    Route::patch('{slug}/{id}', array('as' => 'admin.update','uses' => 'Mascame\Artificer\ModelController@update'));
	Route::delete('model/{slug}/{id}', array('as' => 'admin.destroy', 'uses' => 'Mascame\Artificer\ModelController@destroy'))->before('csrf');

//	Model Sort
//	Route::post('model/{slug}/sort/{old_id}/{new_id}', array('as' => 'admin.sort', 'uses' => 'Mascame\Artificer\Widgets\Sortable\SortableController@sort'));
//	Model Pagination
//	Model File Upload
// Todo: check if this is secure...
	Route::post('model/{slug}/{id}/upload', array('as' => 'admin.upload', 'uses' => 'Mascame\Artificer\ModelController@plupload'));

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

//Route::resource('admin', 'Mascame\Artificer\ModelController');
