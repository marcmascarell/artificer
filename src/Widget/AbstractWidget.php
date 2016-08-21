<?php namespace Mascame\Artificer\Widget;

use Mascame\Artificer\Extension\AbstractExtension;

abstract class AbstractWidget extends AbstractExtension
{

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