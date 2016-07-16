<?php namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Extension\Slugged;

class Manager extends \Mascame\Artificer\Extension\Manager
{
    use Slugged;

    /**
     * @return array
     */
    public function outputRoutes()
    {
        $installedPlugins = $this->installer()->getInstalled();
        
        foreach ($installedPlugins as $plugin) {
            $pluginInstance = $this->get($plugin);

            \Route::group(['prefix' => $pluginInstance->getSlug()], function() use ($pluginInstance) {
                $pluginInstance->getRoutes();
            });
        }
    }

}