<?php

namespace Mascame\Artificer\Extension;

use Mascame\Artificer\Assets\AssetsManagerInterface;
use Mascame\Artificer\Options\PluginOption;

abstract class AbstractExtension
{
    use PublicVendorPaths;

    /**
     * Automatically filled.
     *
     * Namespace will automatically be set if empty (will usually be the class itself).
     * Example: "Mascame\Artificer\Extension\Extension"
     *
     * @var string
     */
    public $namespace;

    /**
     * Automatically filled.
     *
     * Which package is this part of.
     * Example: "mascame/artificer-widgets"
     *
     * @var string
     */
    public $package = null;

    /**
     * Automatically filled.
     *
     * @var array
     */
    public $authors = [];

    /**
     * Automatically filled.
     *
     * @var string
     */
    public $slug;

    /**
     * Name that will be shown on extensions page. Example: "My great extension".
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
     * @var PluginOption
     */
    protected $option;

    /**
     * @var ResourceCollector
     */
    public $resources;

    /**
     * This will be called if the plugin is installed.
     */
    abstract public function boot();

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return Manager
     */
    abstract protected function getManager();

    /**
     * @return bool
     */
    final public function isInstalled()
    {
        return $this->getManager()->isInstalled($this->namespace);
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

    /**
     * Refers to the resources that you would usually place in the ServiceProvider:
     * https://laravel.com/docs/5.3/packages#resources.
     *
     * Keep in mind that extension config is not available until boot
     *
     * @param ResourceCollector $collector
     * @return ResourceCollector
     */
    public function resources(ResourceCollector $collector)
    {
        return $collector;
    }
}
