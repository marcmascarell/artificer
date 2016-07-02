<?php namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Artificer;
use Mascame\Extender\Booter\BooterInterface;

class Booter extends \Mascame\Artificer\Extension\Booter implements BooterInterface {

    /**
     * @var \Mascame\Artificer\Plugin\Manager
     */
    protected $manager;

    public function boot($instance, $name)
    {
        $this->beforeBooting($instance, $name);

        parent::boot($instance, $name);

        $this->afterBooting($instance, $name);
    }

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
