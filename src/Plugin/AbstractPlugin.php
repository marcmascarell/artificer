<?php namespace Mascame\Artificer\Plugin;

use App;
use Mascame\Artificer\Options\PluginOption;

abstract class AbstractPlugin implements PluginInterface
{

    /**
     * Namespace will automatically be set if empty
     *
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $version = '1.0';

    /**
     * @var string
     */
    public $name = 'Unknown plugin';

    /**
     * @var string
     */
    public $description = 'No description provided';

    /**
     * @var string
     */
    public $author = 'Anonymous';

    /**
     * @var string
     */
    public $configFile = null;

    /**
     * @var string
     */
    public $thumbnail = null;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var array
     */
    public $routes = array();

    /**
     * @var PluginOption
     */
    protected $option;

    abstract public function boot();

    /**
     * @return \Mascame\Artificer\Extension\PluginManager
     */
    public function pluginManager() {
        return App::make('ArtificerPluginManager');
    }

    /**
     * @param array $routes
     */
    public function isInstalled()
    {
        return $this->pluginManager()->isInstalled($this->namespace);
    }

    /**
     * @param array $routes
     */
    public function addRoutes(array $routes)
    {
        $this->pluginManager()->addRoutes($this->namespace, $routes);
    }

}