<?php namespace Mascame\Artificer;


use Illuminate\Foundation\Console\VendorPublishCommand;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\ServiceProvider;
use App;
use Config;
use Illuminate\Support\Str;
use Mascame\Artificer\Extension\Booter;
use Mascame\Artificer\Extension\PluginManager;
use Mascame\Artificer\Extension\WidgetManager;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Model\ModelObtainer;
use Mascame\Artificer\Model\ModelSchema;
use Mascame\ArtificerDefaultTheme\ArtificerDefaultThemeServiceProvider;
use Mascame\ArtificerLogreaderPlugin\ArtificerLogreaderPluginServiceProvider;
use Mascame\Artificer\Widgets\ArtificerWidgetsServiceProvider;
use Mascame\Extender\Event\Event;
use Mascame\Extender\Installer\FileInstaller;
use Mascame\Extender\Installer\FileWriter;
use Illuminate\Foundation\AliasLoader as Loader;


class ArtificerServiceProvider extends ServiceProvider {

	use AutoPublishable;
	
	protected $name = 'admin';
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * @var bool
	 */
	protected $isBootable = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		if (! $this->isBootable) return;
		
		$this->addPublishableFiles();

		// Wait until app is ready for config to be published
		if (! $this->isPublished()) return;

		$this->requireFiles();

//		$this->app->register(ArtificerLogreaderPluginServiceProvider::class);
//		$this->app->register(ArtificerWidgetsServiceProvider::class);

		App::make('ArtificerPluginManager')->boot();
		App::make('ArtificerWidgetManager')->boot();

        $this->loadProviders();
        $this->loadAliases();

		$this->commands(config('admin.providers')['commands']);
	}

    protected function loadProviders() {
        $providers = config('admin.providers')['providers'];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }

    protected function loadAliases() {
        $aliases = config('admin.providers')['aliases'];
        $loader = Loader::getInstance();

        foreach ($aliases as $alias => $class) {
            $loader->alias($alias, $class);
        }
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

	protected function getConfigPath() {
		return config_path($this->name) . DIRECTORY_SEPARATOR;
	}

    private function addPublishableFiles()
    {
        $this->publishes([
            __DIR__.'/../config/' => $this->getConfigPath(),
        ], 'config');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->name);

//        $this->publishes([
//            __DIR__.'/../database/migrations/' => database_path('migrations')
//        ], 'migrations');
//
//        $this->publishes([
//            __DIR__.'/../database/seeds/' => database_path('seeds')
//        ], 'seeds');

        $this->publishes([
            __DIR__.'/../resources/assets/' => public_path('packages/mascame/' . $this->name),
        ], 'public');
    }

	private function addModel()
	{
		App::singleton('ArtificerModel', function () {
			return new Model(new ModelSchema(new ModelObtainer()));
		});
	}

	private function addLocalization()
	{
		App::singleton('ArtificerLocalization', function () {
			return new Localization();
		});
	}

	private function addManagers()
	{
		$widgetsConfig = $this->getConfigPath() . 'extensions/widgets.php';

		$widgetManager = new WidgetManager(
			new FileInstaller(new FileWriter(), $widgetsConfig),
			new Booter(),
			new Event(app('events'))
		);
		
		App::singleton('ArtificerWidgetManager', function () use ($widgetManager) {
			return $widgetManager;
		});

		$pluginsConfig = $this->getConfigPath() . 'extensions/plugins.php';

		$pluginManager = new PluginManager(
			new FileInstaller(new FileWriter(), $pluginsConfig),
			new Booter(),
			new Event(app('events'))
		);

		App::singleton('ArtificerPluginManager', function () use ($pluginManager) {
			return $pluginManager;
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$packageConfigPath = __DIR__ . '/../config/admin.php';
        $this->mergeConfigFrom($packageConfigPath, $this->name);

        // Avoid bloating the App with files that will not be needed
		$this->isBootable = $this->isBootable(request()->path(), config('admin.route_prefix'));

		if (! $this->isBootable) return;

		// We need the config loaded before we can use this package!
		if (! $this->isPublished()) {
			$this->autoPublish();
			return;
		}

		$this->addModel();
		$this->addLocalization();
		$this->addManagers();
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
