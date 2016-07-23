<?php namespace Mascame\Artificer\Extension;

use Illuminate\Support\Str;
use Mascame\Extender\Booter\BooterInterface;
use Symfony\Component\CssSelector\XPath\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\File\File;

class Booter extends \Mascame\Extender\Booter\Booter implements BooterInterface {

    /**
     * @var \Mascame\Artificer\Plugin\Manager|\Mascame\Artificer\Widget\Manager
     */
    protected $manager;

    public function boot($instance, $name)
    {
        $this->beforeBooting($instance, $name);

        parent::boot($instance, $name);

        $this->afterBooting($instance, $name);
    }

    /**
     * @param $instance
     * @param $name
     */
    protected function beforeBooting($instance, $name) {
        if (! $instance->namespace) $instance->namespace = $name;
        if (! $instance->name) $instance->name = $name;

        if (! $instance->slug) {
            // For slug readability
            $name = str_replace('\\', '-', $name);
            $instance->slug = Str::slug($name);
        }

        $this->manager->setSlug($instance->slug, $instance->namespace);
    }
    
    /**
     * @param $instance \Mascame\Artificer\Extension\AbstractExtension
     * @param $name
     */
    protected function afterBooting($instance, $name) {
        if (! $this->manager->isInstalled($instance->namespace)) return;

        $this->addAssets($instance);
    }

    /**
     * @param $instance
     */
    protected function addAssets($instance) {
        $package = 'packages/' . $instance->package . '/';

        $assets = $instance->assets();

        $assets = array_map(function($asset) use ($package) {
            return $package . $asset;
        }, $assets);

        \Assets::config([
            // Reset those dirs to avoid wrong paths
            'css_dir' => '',
            'js_dir' => '',
        ])->add($assets);
    }
}
