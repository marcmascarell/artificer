<?php namespace Mascame\Artificer;

use Illuminate\Support\ServiceProvider;
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

		App::singleton('artificer-model', function () {
			return new Model();
		});

        App::singleton('artificer-plugin-manager', function () {
            return new PluginManager();
        });

		App::bind('artificer-command-publish', function () {
			return new PublishCommand();
		});

		$this->commands('artificer-command-publish');
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
