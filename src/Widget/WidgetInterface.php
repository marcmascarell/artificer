<?php namespace Mascame\Artificer\Widget;

use Stolz\Assets\Manager as AssetManager;

interface WidgetInterface
{
    /**
     * @return mixed
     */
    public function assets(AssetManager $manager);

    /**
     * @return mixed
     */
    public function output();

} 