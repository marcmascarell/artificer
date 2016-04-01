<?php namespace Mascame\Artificer;


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
use Mascame\Extender\Event\Event;
use Mascame\Extender\Installer\FileInstaller;
use Mascame\Extender\Installer\FileWriter;
use Illuminate\Foundation\AliasLoader as Loader;


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

		$this->app->register(\Collective\Html\HtmlServiceProvider::class);

		$loader = Loader::getInstance();
		$loader->alias('HTML', \Collective\Html\HtmlFacade::class);
		$loader->alias('Form', \Collective\Html\FormFacade::class);

		$this->app->register(ArtificerDefaultThemeServiceProvider::class);
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
		App::singleton('ArtificerPluginManager', function () {
			$pluginsPath = config_path() . '/'. $this->name .'/plugins.php';

			return new PluginManager(
				new FileInstaller(new FileWriter(), $pluginsPath),
				new Booter(),
				new Event(app('events'))
			);
		});

		App::singleton('ArtificerWidgetManager', function () {
			$widgetsPath = config_path() . '/'. $this->name .'/widgets.php';

			return new WidgetManager(
				new FileInstaller(new FileWriter(), $widgetsPath),
				new Booter(),
				new Event(app('events'))
			);
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
        $this->addManagers();



//		$am = new AssetManager();
//		$am->set('jquery', new HttpAsset('https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js'));
//		$am->set('artificer', new AssetCollection(array(
//			new AssetReference($am, 'jquery'),
//			new FileAsset(__DIR__.'/../resources/assets/core/restfulizer.js'),
//		)));
//
//        $ac = new AssetCollection($am);
//        var_dump($ac->dump()); die();

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
