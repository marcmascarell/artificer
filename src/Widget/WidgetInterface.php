<?php namespace Mascame\Artificer\Widget;

use Stolz\Assets\Manager;

interface WidgetInterface
{
    /**
     * @return mixed
     */
    public function assets(Manager $manager);

    /**
     * @return mixed
     */
    public function output();

} 