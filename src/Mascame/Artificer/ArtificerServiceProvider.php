<?php

namespace Mascame\Artificer;

use App;
use Config;
use Mascame\Artificer\Model\Model;
use Illuminate\Support\ServiceProvider;
use Mascame\Artificer\Model\ModelSchema;
use Mascame\Artificer\Model\ModelObtainer;
use Mascame\Artificer\Plugin\PluginManager;

class ArtificerServiceProvider extends ServiceProvider
{
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
        Config::addNamespace('artificer', app_path('config/packages/mascame/artificer'));

        $this->requireFiles();

        $this->addModel();
        $this->addLocalization();
        $this->addPluginManager();

        $this->addPublishCommand();
    }

    private function requireFiles()
    {
        require_once __DIR__.'/../../filters.php';
        require_once __DIR__.'/../../routes.php';
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
