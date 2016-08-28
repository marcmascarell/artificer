<?php namespace Mascame\Artificer\Extension;

use Illuminate\Support\Str;
use Mascame\Extender\Booter\BooterInterface;

class Booter extends \Mascame\Extender\Booter\Booter implements BooterInterface {

    /**
     * @var \Mascame\Artificer\Plugin\Manager|\Mascame\Artificer\Widget\Manager
     */
    protected $manager;

    protected $initedResources = null;

    public function boot($instance, $name)
    {
        $this->beforeBooting($instance, $name);

        parent::boot($instance, $name);

        $this->afterBooting($instance, $name);
    }

    /**
     * @param $instance AbstractExtension
     * @param $name
     */
    protected function beforeBooting($instance, $name) {

        if (! $instance->namespace) $instance->namespace = $name;
        if (! $instance->name) $instance->name = $name;
        // Todo: remove?
//        if (property_exists($instance, 'assetsPath')) {
//            if (! $instance->assetsPath) $instance->assetsPath = 'packages/' . $instance->package . '/';
//        }

        if (! $instance->slug) {
            $instance->slug = Str::slug(
                str_replace('\\', '-', $name) // For slug readability
            );
        }

        $this->manager->setSlug($instance->slug, $instance->namespace);

        $this->handleResources($instance);
    }

    protected function handleResources($instance) {
        $instance->resources = $instance->resources(new ResourceCollector(app(), get_class($instance)));

        $this->getEventDispatcher()->listen('extender.before.install.' . $instance->namespace, function() use ($instance) {
            $this->initResources($instance)->install();

            if (method_exists($instance, 'install')) $instance->install();
        });

        $this->getEventDispatcher()->listen('extender.before.uninstall.' . $instance->namespace, function() use ($instance) {
            $this->initResources($instance)->uninstall();

            if (method_exists($instance, 'uninstall')) $instance->uninstall();
        });

        // Doing it later would not work
        if ($this->manager->isInstalled($instance->namespace)) {
            $this->initResources($instance);
        }
    }

    /**
     * Initializes resources & avoid multiple initialization
     *
     * @param $instance
     * @return ResourceInstaller|null
     */
    protected function initResources($instance) {
        if ($this->initedResources) return $this->initedResources;

        return $this->initedResources = (new ResourceInstaller(app(), $instance));
    }

    /**
     * @param $instance \Mascame\Artificer\Extension\AbstractExtension
     * @param $name
     */
    protected function afterBooting($instance, $name) {

    }



}
