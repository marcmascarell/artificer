<?php namespace Mascame\Artificer\Widget;

use Mascame\Artificer\Extension\Slugged;

class Manager extends \Mascame\Artificer\Extension\Manager
{
    use Slugged;

    protected $type = 'widgets';
}