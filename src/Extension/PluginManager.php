<?php namespace Mascame\Artificer\Extension;

use Mascame\Artificer\Plugin\AbstractPlugin;

class PluginManager extends Manager
{

    protected $routes = [];

    /**
     * @param $plugin
     * @param $array
     */
    public function addRoutes($plugin, $array)
    {
        if (! $this->isInstalled($plugin)) return;

        $this->routes[$plugin] = $array;
    }
}