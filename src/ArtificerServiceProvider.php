<?php namespace Mascame\Artificer;

use Illuminate\Support\ServiceProvider;
use App;
use Config;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Model\ModelObtainer;
use Mascame\Artificer\Model\ModelSchema;
use Mascame\Artificer\Plugin\PluginManager;


class ArtificerServiceProvider extends ServiceProvider {

	protected $name = 'admin';
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
		$this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->name);

		$this->publishes([
			__DIR__.'/../config/' => config_path($this->name) .'/',
		]);

        $this->requireFiles();

		$this->addModel();
		$this->addLocalization();
		$this->addPluginManager();

//		$this->addPublishCommand();
//		dd(config($this->name));
	}

	private function requireFiles()
	{
		require_once __DIR__ . '/Http/filters.php';
		require_once __DIR__ . '/Http/routes.php';
	}

	private function addPublishCommand()
	{
		$command_key = 'artificer-command-publish';

		App::bind($command_key, function () {
			return new PublishCommand();
		});

		$this->commands($command_key);
	}

	private function addModel()
	{
		App::singleton('artificer-model', function () {
			return new Model(new ModelSchema(new ModelObtainer()));
		});
	}

	private function addLocalization()
	{
		App::singleton('artificer-localization', function () {
			return new Localization();
		});
	}

	private function addPluginManager()
	{
		App::singleton('artificer-plugin-manager', function () {
			return new PluginManager();
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$configPath = __DIR__ . '/../config/admin.php';
		$this->mergeConfigFrom($configPath, $this->name);
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
