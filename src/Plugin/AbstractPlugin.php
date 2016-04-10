<?php namespace Mascame\Artificer\Plugin;

use App;
use Mascame\Artificer\Extension\AbstractExtension;

abstract class AbstractPlugin extends AbstractExtension implements PluginInterface
{

    /**
     * @return \Mascame\Artificer\Extension\PluginManager
     */
    public function getManager() {
        return App::make('ArtificerPluginManager');
    }

}