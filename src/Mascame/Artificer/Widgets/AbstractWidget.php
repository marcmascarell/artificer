<?php

namespace Mascame\Artificer\Widgets;

abstract class AbstractWidget
{
    public $name;
    public $package_assets = '/packages/mascame/artificer-widgets';

    public function __construct()
    {
        $this->name = get_called_class();

        return $this;
    }

    public function output()
    {
        return false;
    }
}
