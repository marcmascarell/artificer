<?php namespace Mascame\Artificer\Extension;

use Mascame\Artificer\Assets\AssetsManagerInterface;
use Mascame\Artificer\Options\PluginOption;

abstract class AbstractExtension
{

    public $assetsPath = null;

    /**
     * Automatically filled
     *
     * Namespace will automatically be set if empty (will usually be the class itself).
     * Example: "Mascame\Artificer\Extension\Extension"
     *
     * @var string
     */
    public $namespace;

    /**
     * Automatically filled
     *
     * Which package is this part of.
     * Example: "mascame/artificer-widgets"
     *
     * @var string
     */
    public $package = null;

    /**
     * Automatically filled
     *
     * @var array
     */
    public $authors = [];

    /**
     * Automatically filled
     *
     * @var string
     */
    public $slug;

    /**
     * Name that will be shown on extensions page. Example: "My great extension"
     *
     * @var string
     */
    public $name = null;

    /**
     * @var string
     */
    public $description = 'No description provided';

    /**
     * @var string
     */
    public $thumbnail = null;

    /**
     * @var string
     */
    public $configPath = null;

    /**
     * Automatically set on set configPath
     *
     * @var string
     */
    public $configDotNotationPath = null;

    /**
     * @var PluginOption
     */
    protected $option;

    /**
     * @var ResourceCollector
     */
    public $resources;

    abstract public function boot();

    public function getSlug() {
        return $this->slug;
    }

    final public function getConfig($key = null, $default = null)
    {
        if ($key) $key = "." . $key;

        return config($this->getConfigDotNotationPath() . $key, $default);
    }

    final public function getConfigDotNotationPath() {
        return str_replace('/', '.', $this->getShortConfigPath());
    }

    final public function getConfigPath()
    {
        return config_path($this->getShortConfigPath());
    }

    private function getShortConfigPath()
    {
        return 'admin/extensions/' . $this->slug;
    }

    /**
     * @return Manager
     */
    abstract protected function getManager();

    /**
     * @return bool
     */
    public final function isInstalled()
    {
        return $this->getManager()->isInstalled($this->namespace);
    }

    /**
     * vendor/package
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->namespace;
    }

    /**
     * The assets manager will request the desired assets.
     *
     * Plugin assets: Will always be requested
     * Widget assets: Will only be requested when needed
     *
     * Note: If you are using local assets they should be published (only happens if the extension is installed)
     *
     * Example: [ $this->assetsPath . 'css/my-style.css' ]
     *
     * @param AssetsManagerInterface $manager
     * @return AssetsManagerInterface
     */
    public function assets(AssetsManagerInterface $manager)
    {
        return $manager;
    }

    public function resources(ResourceCollector $collector)
    {
        return $collector;
    }

    /**
     * Register a view file namespace.
     *
     * @param  string  $path
     * @param  string  $namespace
     * @return void
     */
    protected function loadViewsFrom($path, $namespace)
    {
        if (is_dir($appPath = app()->basePath().'/resources/views/vendor/'.$namespace)) {
            app()['view']->addNamespace($namespace, $appPath);
        }

        app()['view']->addNamespace($namespace, $path);
    }
}