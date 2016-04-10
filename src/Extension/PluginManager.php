<?php namespace Mascame\Artificer\Extension;

class PluginManager extends \Mascame\Extender\Manager
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