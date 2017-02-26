<?php

namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Artificer;
use Mascame\Extender\Booter\BooterInterface;
use Mascame\Artificer\Extension\AbstractExtension;

class Booter extends \Mascame\Artificer\Extension\Booter implements BooterInterface
{
    /**
     * @var \Mascame\Artificer\Plugin\Manager
     */
    protected $manager;

    /**
     * @param $instance AbstractPlugin
     * @param $name
     */
    public function afterBooting($instance, $name)
    {
        if (! $this->manager->isInstalled($instance->namespace)) {
            return;
        }

        if ($menu = $instance->getMenu()) {
            Artificer::addMenu($menu);
        }

        $this->addAssets($instance);
    }

    /**
     * @param $instance AbstractExtension
     */
    protected function addAssets($instance)
    {
        $assetsManager = Artificer::assetManager();

        $instance->assets($assetsManager);
    }
}
