<?php namespace Mascame\Artificer\Extension;

use Illuminate\Support\Str;
use Mascame\Extender\Booter\BooterInterface;

class Booter extends \Mascame\Extender\Booter\Booter implements BooterInterface {

    /**
     * @var \Mascame\Artificer\Extension\PluginManager
     */
    protected $manager;

    /**
     * @param $instance
     * @param $name
     */
    public function setProperties($instance, $name) {
        if (! $instance->namespace) $instance->namespace = $name;
        if (! $instance->slug) $instance->slug = Str::slug($name);

        $this->manager->setSlug($instance->slug, $instance->namespace);
    }
}
