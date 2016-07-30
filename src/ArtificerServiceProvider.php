<?php namespace Mascame\Artificer;

use App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Mascame\Artificer\Extension\Booter;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Model\ModelObtainer;
use Mascame\Artificer\Model\ModelSchema;
use Mascame\Artificer\Widget\Manager as WidgetManager;
use Mascame\Artificer\Plugin\Manager as PluginManager;
use Mascame\Extender\Event\Event;
use Mascame\Extender\Installer\FileInstaller;
use Mascame\Extender\Installer\FileWriter;


class ArtificerServiceProvider extends ServiceProvider {

	use AutoPublishable, ServiceProviderLoader;
	
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

		$this->providers(config('admin.providers'));
		$this->aliases(config('admin.aliases'));
		$this->commands(config('admin.commands'));

		App::make('ArtificerWidgetManager')->boot();
		App::make('ArtificerPluginManager')->boot();

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
		require_once __DIR__ . '/../routes/admin.php';
	}

	protected function getConfigPath() {
		return config_path($this->name) . DIRECTORY_SEPARATOR;
	}

	private function addPublishableFiles()
    {
		$this->publishes([
			__DIR__.'/../resources/assets' => public_path('packages/mascame/' . $this->name),
		], 'public');

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
			new \Mascame\Artificer\Plugin\Booter(),
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
		// We still haven't modified config, that's why 'admin.admin'
		$routePrefix = config('admin.admin.routePrefix');

		// Avoid bloating the App with files that will not be needed
		$this->isBootable = $this->isBootable(request()->path(), $routePrefix);

		if (! $this->isBootable) return;

		// We need the config published before we can use this package!
		if ($this->isPublished()) {
			$this->loadConfig();

			$this->addModel();
			$this->addLocalization();
			$this->addManagers();
		}
	}

	/**
	 * Moves admin/admin.php keys to the root level for commodity
	 */
	protected function loadConfig() {
		$config = config('admin');
		$config = ['admin' => array_merge($config, $config['admin'])];
		unset($config['admin']['admin']);

		config()->set($config);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
