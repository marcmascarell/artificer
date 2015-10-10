<?php namespace Mascame\Artificer\Extension;

use Illuminate\Support\Str;
use Mascame\Artificer\Plugin\AbstractPlugin;

class Manager {

    /**
     * @var array
     */
    protected $extensions = [];
    protected $extensionInstances = [];
    protected $extensionSlugs = [];

    /**
     * @var Installer
     */
    protected $installer;

    protected $booted = false;

    public function __construct($configFile)
    {
        $this->installer = new Installer($configFile);
    }

    public function installer() {
        return $this->installer;
    }

    public function getAll()
    {
        return $this->extensionInstances;
    }

    public function getRegistered()
    {
        return array_keys($this->extensions);
    }

    public function add($name, \Closure $plugin)
    {
        $this->extensions[$name] = $plugin;
    }

    protected function getInstance($name)
    {
        if (array_key_exists($name, $this->extensionInstances)) {
            return $this->extensionInstances[$name];
        }

        return $this->extensions[$name]();
    }

    public function isInstalled($name) {
        return $this->installer->isInstalled($name);
    }

    public function boot()
    {
        if ($this->booted) return;

        foreach ($this->extensions as $name => $closure) {
            /**
             * @var $instance AbstractPlugin
             */
            $instance = $this->getInstance($name);

            $this->setProperties($instance, $name);

            if ($this->isInstalled($name)) {
                $instance->boot();

                $this->extensionInstances['installed'][$name] = $instance;
            } else {
                $this->extensionInstances['uninstalled'][$name] = $instance;
            }
        }

        $this->installer->handleExtensionChanges(array_keys($this->extensions));

        $this->booted = true;
    }

    public function getFromSlug($slug) {
        return $this->getInstance($this->extensionSlugs[$slug]);
    }

    protected function setProperties($instance, $name) {
        if (! $instance->namespace) $instance->namespace = $name;
        if (! $instance->slug) $instance->slug = Str::slug($name);

        $this->extensionSlugs[$instance->slug] = $instance->namespace;
    }
}
