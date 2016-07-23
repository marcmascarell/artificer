<?php namespace Mascame\Artificer\Widget;

use Mascame\Artificer\Extension\AbstractExtension;

abstract class AbstractWidget extends AbstractExtension implements WidgetInterface
{

    /**
     * Will output the assets when the extension is installed directly to the vendor
     *
     * /packages/namespace/package-name/ will be prepended automatically
     *
     * Example: ['css/my-style.css']
     *
     * @return array
     */
    public function assets()
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