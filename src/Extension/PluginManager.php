<?php namespace Mascame\Artificer\Extension;

use Mascame\Extender\Manager;

class PluginManager extends Manager
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $extensionSlugs = [];

    /**
     * @param $slug
     * @param $name
     */
    public function setSlug($slug, $name) {
        $this->extensionSlugs[$slug] = $name;
    }

    /**
     * @param $plugin
     * @param $array
     */
    public function addRoutes($plugin, $array)
    {
        if (! $this->isInstalled($plugin)) return;

        $this->routes[$plugin] = $array;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getFromSlug($slug) {
        return $this->getInstance($this->extensionSlugs[$slug]);
    }
}