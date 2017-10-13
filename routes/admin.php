<?php

use Mascame\Artificer\Controllers\ModelController as ModelController;
use Mascame\Artificer\Controllers\InstallController as InstallController;
use Mascame\Artificer\Controllers\ExtensionController as ExtensionController;

//dd(\Illuminate\Support\Facades\Hash::make('artificer'));
//$user = \Mascame\Artificer\ArtificerUser::find(1);
//
//\Mascame\Artificer\Model\Permission\Role::create(['name' => 'editor']);
//\Mascame\Artificer\Model\Permission\Role::create(['name' => 'admin']);
//\Mascame\Artificer\Model\Permission\Role::create(['name' => 'writer']);
//
//$perms = new \Mascame\Artificer\Model\Permission\Permission();
//$perms->forgetCachedPermissions();
//
//$user->assignRole('writer');
//$user->assignRole('admin');
////
//dd($user->hasRole('admin'));

Route::pattern('id', '\d+');
Route::pattern('slug', '[a-z0-9_-]+');

Route::group([
    'middleware' => [
        'web',
        'artificer',
    ],
    'prefix' => \Mascame\Artificer\Options\AdminOption::get('route_prefix'),
], function () {
    Route::group(['middleware' => 'artificer-installed', 'prefix' => 'install'], function () {
        Route::get('/', ['as' => 'admin.install', 'uses' => InstallController::class.'@home']);
        Route::post('/', InstallController::class.'@install');
    });

    \Mascame\Artificer\Artificer::pluginManager()->outputCoreRoutes();

    Route::group(['middleware' => ['artificer-auth']], function () {
        Route::get('extensions', ExtensionController::class.'@extensions')->name('admin.extensions');

        foreach (['plugins', 'widgets'] as $extensionType) {
            Route::group(['prefix' => $extensionType], function () use ($extensionType) {
                Route::get('{slug}/install', ExtensionController::class.'@install')->name('admin.'.$extensionType.'.install');
                Route::get('{slug}/uninstall', ExtensionController::class.'@uninstall')->name('admin.'.$extensionType.'.uninstall');
            });
        }

        Route::group(['prefix' => 'api/model'], function () {
            Route::get('{model}', ModelController::class.'@all')->name('admin.model.all');
            Route::get('{model}/filter', ModelController::class.'@filter')->name('admin.model.filter');
            Route::get('{model}/create', ModelController::class.'@create')->name('admin.model.create');
            Route::get('{model}/{id}', ModelController::class.'@show')->name('admin.model.show');
            Route::get('{model}/{id}/edit', ModelController::class.'@edit')->name('admin.model.edit');
            Route::get('{model}/{id}/edit/{field}', ModelController::class.'@field')->name('admin.model.field.edit');
            Route::post('{model}/store', ModelController::class.'@updateOrCreate')->name('admin.model.store');
//            Route::post('{model}/upload', ModelController::class.'@upload')->name('admin.model.upload');
            Route::put('{model}/{id}', ModelController::class.'@updateOrCreate')->name('admin.model.update');
            Route::delete('{model}/{id}', ModelController::class.'@destroy')->name('admin.model.destroy');

            Route::get('{model}/{id}/field/{name}', ModelController::class.'@getRelatedFieldOutput')->name('admin.model.field');
        });

        Route::group(['prefix' => 'plugin'], function () {
            \Mascame\Artificer\Artificer::pluginManager()->outputRoutes();
        });

        Route::get('/{catchall?}', \Mascame\Artificer\Controllers\BaseController::class.'@delegateToVue')
            ->name('admin.home')
            ->where('catchall', '(.*)');
    });
});
