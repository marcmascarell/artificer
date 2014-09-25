<?php namespace Mascame\Artificer;

use Illuminate\Support\ServiceProvider;
use Config;
use App;


class ArtificerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('mascame/artificer');
		require_once __DIR__ . '/../../filters.php';
		require_once __DIR__ . '/../../routes.php';
		require_once __DIR__ . '/../../views/macros/macros.php';

//		$this->app->bind('admin.admin', function($app) {
//			return new AdminCommand();
//		});
//
//		$this->commands(array(
//			'admin'
//		));

		App::singleton('artificer-model', function () {
			return new Model();
		});

		Notification::attach();

		$this->app['artificer'] = $this->app->share(function ($app) {
			return new PublishCommand();
		});
		$this->commands('artificer');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		/*
     * Register the service provider for the dependency.
     */
//		$this->app->register('JildertMiedema\LaravelPlupload\LaravelPluploadServiceProvider');
//		/*
//		 * Create aliases for the dependency.
//		 */
//		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
//		$loader->alias('Plupload', 'JildertMiedema\LaravelPlupload\Facades\Plupload');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
