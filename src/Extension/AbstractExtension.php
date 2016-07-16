<?php namespace Mascame\Artificer\Extension;

use App;
use Mascame\Artificer\Options\PluginOption;
use Mascame\Extender\Manager;

abstract class AbstractExtension
{

    /**
     * Namespace will automatically be set if empty
     *
     * @var string
     */
    public $namespace;

    /**
     * Semver http://semver.org/
     *
     * @var string
     */
    public $version = null;

    /**
     * Name that will be shown on extensions page
     *
     * @var string
     */
    public $name = null;

    /**
     * @var string
     */
    public $description = 'No description provided';

    /**
     * @var string
     */
    public $author = 'Anonymous';

    /**
     * @var string
     */
    public $configFile = null;

    /**
     * @var string
     */
    public $thumbnail = null;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var PluginOption
     */
    protected $option;

    abstract public function boot();

    public function getSlug() {
        return $this->slug;
    }
    
    /**
     * @return Manager
     */
    abstract function getManager();

    /**
     * @param array $routes
     */
    public final function isInstalled()
    {
        return $this->getManager()->isInstalled($this->namespace);
    }

}