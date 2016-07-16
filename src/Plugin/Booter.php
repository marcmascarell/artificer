<?php namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Artificer;
use Mascame\Extender\Booter\BooterInterface;

class Booter extends \Mascame\Artificer\Extension\Booter implements BooterInterface {

    /**
     * @var \Mascame\Artificer\Plugin\Manager
     */
    protected $manager;


    /**
     * @param $instance AbstractPlugin
     * @param $name
     */
    public function afterBooting($instance, $name) {
        if (! $this->manager->isInstalled($name)) return;

        if ($menu = $instance->getMenu()) {
            Artificer::addMenu($menu);
        }
    }
}
