<?php namespace Mascame\Artificer\Extension;

class PluginManager extends \Mascame\Extender\Manager
{
    use Slugged;

    /**
     * @var array
     */
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