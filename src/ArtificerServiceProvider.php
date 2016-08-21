<?php namespace Mascame\Artificer;

use App;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Mascame\Artificer\Assets\AssetsManager;
use Mascame\Artificer\Commands\MigrationCommands;
use Mascame\Artificer\Extension\Booter;
use Mascame\Artificer\Model\ModelManager;
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

    protected $corePlugins = [
        \Mascame\Artificer\LoginPlugin::class
    ];

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

        Artificer::pluginManager()->boot();
        Artificer::widgetManager()->boot();

//        Artificer::pluginManager()->installer()->uninstall(\Mascame\Artificer\LoginPlugin::class);

        $this->manageCorePlugins();

        Artificer::assetManager()->add(config('admin.assets', []));

        if (! $this->app->routesAreCached()) {
            require_once __DIR__ . '/../routes/admin.php';
        }
	}

    /**
     * Ensure core plugins are installed
     *
     * @throws \Exception
     */
	protected function manageCorePlugins() {
	    // Avoid installing plugins when using CLI
        if (App::runningInConsole() || App::runningUnitTests()) return true;

        $pluginManager = Artificer::pluginManager();
        $needsRefresh = false;

        foreach ($this->corePlugins as $corePlugin) {
            if (! $pluginManager->isInstalled($corePlugin)) {
                $installed = $pluginManager->installer()->install($corePlugin);

                if (! $installed) {
                    throw new \Exception("Unable to install Artificer core plugin {$corePlugin}");
                }

                $needsRefresh = true;
            }
        }

        // Refresh to allow changes made by core plugins to take effect
        if ($needsRefresh) {
            /**
             * File driver is slow... wait some seconds (else we would have too many redirects)
             *
             * Fortunately we only do this in the first run. Ye, I don't like it either.
             */
            sleep(2);

            header('Location: '. \URL::current());
            die();
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

	protected function getConfigPath() {
		return config_path($this->name) . DIRECTORY_SEPARATOR;
	}

	private function addPublishableFiles()
    {
        $this->autoPublishes(function() {
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('packages/mascame/' . $this->name),
            ], 'public');

            $this->publishes([
                __DIR__.'/../config/' => $this->getConfigPath(),
            ], 'config');

            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->name);
        });
    }

	private function getExtensionInstaller($type) {
        if (config('admin.extension_driver') == 'file') {
            $extensionConfig = $this->getConfigPath() . 'extensions/'. $type .'.php';

            return new FileInstaller(new FileWriter(), $extensionConfig);
        }
    }

	private function registerBindings()
	{
        App::singleton('ArtificerModelManager', function () {
            return new ModelManager(new ModelSchema(new ModelObtainer()));
        });

		App::singleton('ArtificerWidgetManager', function() {
            return new WidgetManager(
                $this->getExtensionInstaller('widgets'),
                new Booter(),
                new Event(app('events'))
            );
        });

		App::singleton('ArtificerPluginManager', function() {
            return new PluginManager(
                $this->getExtensionInstaller('plugins'),
                new \Mascame\Artificer\Plugin\Booter(),
                new Event(app('events'))
            );
		});

        App::singleton('ArtificerAssetManager', function() {
            return (new AssetsManager())->config(array_merge([
                // Reset those dirs to avoid wrong paths
                'css_dir' => '',
                'js_dir' => '',
            ], config('admin.assets')));
        });

        App::singleton('ArtificerMigrationRepository', function() {
            return new DatabaseMigrationRepository(app('db'), 'artificer_migrations');
        });

        App::singleton('ArtificerMigrator', function() {
            return new \Illuminate\Database\Migrations\Migrator(app('ArtificerMigrationRepository'), app('db'), app('files'));
        });

        // Generates a copy of migration commands with prepending 'artificer:'
        new MigrationCommands(app('ArtificerMigrator'), app('ArtificerMigrationRepository'));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// We still haven't modified config, that's why 'admin.admin'
		$routePrefix = config('admin.admin.route_prefix');

		// Avoid bloating the App with files that will not be needed
		$this->isBootable = $this->isBootable(request()->path(), $routePrefix);

		if (! $this->isBootable) return;

		// We need the config published before we can use this package!
		if ($this->isPublished()) {
			$this->loadConfig();

            // Todo
//			$this->addLocalization();
			$this->registerBindings();
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
