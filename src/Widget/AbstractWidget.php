<?php namespace Mascame\Artificer\Widget;

use Mascame\Artificer\Extension\AbstractExtension;
use Stolz\Assets\Manager as AssetsManager;

abstract class AbstractWidget extends AbstractExtension implements WidgetInterface
{

    public $assetsPath = null;

    /**
     * Plugins: Will output the assets when the extension is installed directly to the vendor
     * Widgets: Will output the assets when necessary
     *
     * Example: [ $this->assetsPath . 'css/my-style.css' ]
     *
     * @return array
     */
    public function assets(AssetsManager $manager)
    {
        return [];
    }

    /**
     * vendor/package
     * 
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->namespace;
    }

    /**
     * @return null
     */
    public function output()
    {
        return null;
    }

    /**
     * @return Manager
     */
    public function getManager() {
        return \App::make('ArtificerWidgetManager');
    }

    public function boot()
    {
        return null;
    }

}