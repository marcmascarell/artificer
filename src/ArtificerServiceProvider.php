<?php

namespace Mascame\Artificer;

use App;
use Mascame\Hooky\Hook;
use Illuminate\Support\Str;
use Mascame\Extender\Event\Event;
use Illuminate\Support\ServiceProvider;
use Mascame\Artificer\Extension\Booter;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Model\ModelObtainer;
use Mascame\Artificer\Assets\AssetsManager;
use Mascame\Artificer\Commands\MigrationCommands;
use Mascame\Artificer\Extension\DatabaseInstaller;
use Mascame\Artificer\Plugin\Manager as PluginManager;
use Mascame\Artificer\Widget\Manager as WidgetManager;
use Mascame\Artificer\Providers\InstallServiceProvider;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class ArtificerServiceProvider extends ServiceProvider
{
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
        if (! $this->isBootable) {
            return;
        }

        $this->addPublishableFiles();

        // Wait until app is ready for config to be published
        if (! $this->isPublished()) {
            return;
        }

        $this->addMiddleware();

        $this->providers(config('admin.providers'));
        $this->aliases(config('admin.aliases'));
        $this->commands(config('admin.commands'));

        Artificer::assetManager()->add(config('admin.assets', []));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'artificer');

        if (InstallServiceProvider::isExtensionDriverReady()) {
            Artificer::pluginManager()->boot();
            Artificer::widgetManager()->boot();
        }

        if (! $this->app->routesAreCached()) {
            require_once __DIR__.'/../routes/admin.php';
        }
    }

    protected function addMiddleware()
    {
        \App::make('router')->middlewareGroup('artificer', []);
        \App::make('router')->middlewareGroup('artificer-auth', []);
    }

    /**
     * Determines if admin is bootable.
     *
     * @param $path
     * @param null $routePrefix
     * @return bool
     */
    public function isBootable($path, $routePrefix = null)
    {
        if (App::runningInConsole() || App::runningUnitTests()) {
            return true;
        }

        return
            $path == $routePrefix || Str::startsWith($path, $routePrefix.'/');
    }

    protected function getConfigPath()
    {
        return config_path($this->name).DIRECTORY_SEPARATOR;
    }

    private function addPublishableFiles()
    {
        $this->autoPublishes(function () {
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path(Artificer::getAssetsPath()),
            ], 'public');

            $this->publishes([
                __DIR__.'/../config/' => $this->getConfigPath(),
            ], 'config');

            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->name);
        });
    }

    private function getExtensionInstaller($type)
    {
        if (config('admin.extension_driver') == 'database') {
            return new DatabaseInstaller($type);
        }

        return new \Exception('Missing extension installer driver.');
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

        if (! $this->isBootable) {
            return;
        }

        // We need the config published before we can use this package!
        if ($this->isPublished()) {
            $this->loadConfig();

            // Todo
            //			$this->addLocalization();
            $this->registerBindings();
        }
    }

    /**
     * Moves admin/admin.php keys to the root level for commodity.
     */
    protected function loadConfig()
    {
        $config = config('admin');
        $config = ['admin' => array_merge($config, $config['admin'])];
        unset($config['admin']['admin']);

        config()->set($config);
    }

    private function registerBindings()
    {
        App::singleton('ArtificerHook', function () {
            return new Hook();
        });

        /*
        |--------------------------------------------------------------------------
        | Register commands
        |--------------------------------------------------------------------------
        |*/

        App::singleton('ArtificerMigrationRepository', function () {
            return new DatabaseMigrationRepository(app('db'), config('admin.migrations'));
        });

        App::singleton('ArtificerMigrator', function () {
            return new \Illuminate\Database\Migrations\Migrator(app('ArtificerMigrationRepository'), app('db'), app('files'));
        });

        // Generates a copy of migration commands with prepending 'artificer:'
        new MigrationCommands(app('ArtificerMigrator'), app('ArtificerMigrationRepository'));

        /*
        |--------------------------------------------------------------------------
        | Register managers
        |--------------------------------------------------------------------------
        |*/

        App::singleton('ArtificerModelManager', function () {
            return new ModelManager(new ModelObtainer());
        });

        App::singleton('ArtificerWidgetManager', function () {
            return new WidgetManager(
                $this->getExtensionInstaller('widgets'),
                new Booter(),
                new Event(app('events'))
            );
        });

        App::singleton('ArtificerPluginManager', function () {
            return new PluginManager(
                $this->getExtensionInstaller('plugins'),
                new \Mascame\Artificer\Plugin\Booter(),
                new Event(app('events'))
            );
        });

        App::singleton('ArtificerAssetManager', function () {
            return (new AssetsManager())->config(array_merge([
                // Reset those dirs to avoid wrong paths
                'css_dir' => '',
                'js_dir' => '',
            ], config('admin.assets')));
        });
    }
}
