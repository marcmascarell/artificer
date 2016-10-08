<?php

namespace Mascame\Artificer\Plugin;

use App;
use Mascame\Artificer\Extension\AbstractExtension;

abstract class AbstractPlugin extends AbstractExtension implements PluginInterface
{
    /**
     * @return Manager
     */
    public function getManager()
    {
        return App::make('ArtificerPluginManager');
    }

    /**
     * Return your plugin routes.
     *
     * @return \Closure|null
     */
    public function getRoutes()
    {
    }

    /**
     * Return your plugin menu entries.
     *
     * @return array|null
     */
    public function getMenu()
    {
    }
}
