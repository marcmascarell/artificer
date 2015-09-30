<?php namespace Mascame\Artificer\Widgets;

abstract class AbstractWidget
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public static $package_assets = '/packages/mascame/artificer-widgets';

    public function __construct()
    {
        $this->name = get_called_class();
    }

    /**
     * @return null
     */
    public function output()
    {
        return null;
    }

}