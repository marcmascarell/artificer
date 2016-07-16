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
    public function beforeBooting($instance, $name) {
        $instance->namespace = $name;

        if (! $instance->version) {
            $instance->version = $this->manager->getVersion($instance->namespace);
        }

        if (! $instance->name) $instance->name = $name;

        if (! $instance->slug) $instance->slug = Str::slug($name);

        $this->manager->setSlug($instance->slug, $instance->namespace);
    }
    
    /**
     * @param $instance \Mascame\Artificer\Extension\AbstractExtension
     * @param $name
     */
    public function afterBooting($instance, $name) {
        if (! $this->manager->isInstalled($instance->namespace)) return;

        // Todo: add assets
//die('here!');
//        \Assets::config([
//            'group1' => [
//
//            ],
//        ]);
        $package = $instance->namespace . '/' . $instance->slug;

        \Assets::config([

        ])->add(["$package:bar.css", "$package:foo.js"]);
    }
}
