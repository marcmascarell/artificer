<?php namespace Mascame\Artificer;

use Illuminate\Support\ServiceProvider;
use App;
use Config;
use Illuminate\Support\Str;
use Mascame\Artificer\Extension\PluginManager;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Model\ModelObtainer;
use Mascame\Artificer\Model\ModelSchema;


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
        if (! Artificer::isBooted()) return;

		$this->addPublishing();
        $this->requireFiles();
	}

    /**
     * Determines if is on admin
     *
     * @return bool
     */
    public function isBootable($path, $routePrefix = null) {
        if (App::runningInConsole() || App::runningUnitTests()) return true;

        return (
            $path == $routePrefix || Str::startsWith($path, $routePrefix . '/')
        );
    }

	private function requireFiles()
	{
		require_once __DIR__ . '/Http/filters.php';
		require_once __DIR__ . '/Http/routes.php';
	}

    private function addPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/' => config_path($this->name) .'/',
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->name);

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../database/seeds/' => database_path('seeds')
        ], 'seeds');

        $this->publishes([
            __DIR__.'/../resources/assets/' => public_path('packages/mascame/' . $this->name),
        ], 'public');
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

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $configPath = __DIR__ . '/../config/admin.php';
        $this->mergeConfigFrom($configPath, $this->name);

        // Avoid bloating the App with files that will not be needed
        if (! $this->isBootable(request()->path(), config('admin.route_prefix'))) return;

        $this->addModel();
        $this->addLocalization();

		App::singleton('ArtificerPluginManager', function () {
			return new PluginManager(config_path() . '/'. $this->name .'/plugins.php');
		});

        Artificer::$booted = true;
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
