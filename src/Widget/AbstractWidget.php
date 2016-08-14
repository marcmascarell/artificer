<?php namespace Mascame\Artificer\Widget;

use Mascame\Artificer\Extension\AbstractExtension;

abstract class AbstractWidget extends AbstractExtension implements WidgetInterface
{

    public $assetsPath = null;

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