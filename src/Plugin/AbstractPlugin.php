<?php namespace Mascame\Artificer\Plugin;

use App;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Extension\AbstractExtension;

abstract class AbstractPlugin extends AbstractExtension implements PluginInterface
{

    /**
     * @return \Mascame\Artificer\Extension\PluginManager
     */
    public function getManager() {
        return App::make('ArtificerPluginManager');
    }

    /**
     * Return your plugin routes.
     *
     * @return \Closure|null
     */
    public function getRoutes() {
        return null;
    }

    /**
     * Return your plugin menu entries.
     * 
     * @return array|null
     */
    public function getMenu() {
        return null;
    }

    // TODO: this should be done in boot! getting array from getMenu
//    public function addMenu($menu) {
//        if (! $this->isInstalled()) {
//            throw new \Exception('Artificer menus can\'t be added unless plugin is installed. Don\'t call this method in constructor.');
//        }
//
//        Artificer::addMenu($menu);
//    }

}