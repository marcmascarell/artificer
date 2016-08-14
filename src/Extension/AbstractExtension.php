<?php namespace Mascame\Artificer\Extension;

use Mascame\Artificer\Options\PluginOption;
use Stolz\Assets\Manager as AssetsManager;

abstract class AbstractExtension
{

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
    public $configFile = null;

    /**
     * @var PluginOption
     */
    protected $option;

    abstract public function boot();

    public function getSlug() {
        return $this->slug;
    }
    
    /**
     * @return Manager
     */
    abstract function getManager();

    /**
     * @return bool
     */
    public final function isInstalled()
    {
        return $this->getManager()->isInstalled($this->namespace);
    }

    /**
     * Plugins: Will output the assets when the extension is installed directly to the vendor
     * Widgets: Will output the assets when necessary
     *
     * Example: [ $this->assetsPath . 'css/my-style.css' ]
     *
     * @return array
     */
    public function assets(AssetsManager $manager)
    {
        return [];
    }


}