<?php namespace Mascame\Artificer\Extension;

use Illuminate\Support\Str;
use Mascame\Extender\Booter\BooterInterface;
use Mascame\Extender\Event\EventInterface;
use Mascame\Extender\Installer\InstallerInterface;
use Symfony\Component\CssSelector\XPath\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\File\File;

class Manager extends \Mascame\Extender\Manager {

    /**
     * @var \Mascame\Artificer\Plugin\Manager|\Mascame\Artificer\Widget\Manager
     */
    protected $manager;

    /**
     * Composer packages
     * 
     * @var array
     */
    protected $packages = [];

    /**
     * @param InstallerInterface $installer
     * @param BooterInterface|null $booter
     * @param EventInterface|null $eventDispatcher
     * @throws \Exception
     */
    public function __construct(
        InstallerInterface $installer,
        BooterInterface $booter = null,
        EventInterface $eventDispatcher = null
    )
    {
        parent::__construct($installer, $booter, $eventDispatcher);

        $this->packages = $this->getPackagesVersions();
    }

    /**
     * @param $name
     * @param \Closure $plugin
     */
    public function add($name, \Closure $plugin)
    {
        if (! $this->isValidNamespace($name)) {
            throw new \Exception('Extension namespace is mandatory and must be compliant to "vendor/package". Provided: ' . $name);
        }

        parent::add($name, $plugin);
    }
    
    /**
     * vendor/package
     *
     * @param $namespace
     * @return bool
     */
    protected function isValidNamespace($namespace) {
        $regex = "/^[\\w-]+\\/[\\w-]+$/";

        preg_match($regex, $namespace, $matches);

        return (count($matches) > 0);
    }

    /**
     * @return array
     */
    protected function getPackagesVersions() {
        $installedPackagesFile = config('admin.vendorPath') . '/composer/installed.json';
        $packagesWithName = [];
        
        if (\File::exists($installedPackagesFile)) {
            $packages = json_decode(\File::get($installedPackagesFile), true);

            $packagesWithName = [];

            foreach ($packages as $package) {
                $packagesWithName[$package['name']] = $package;
            }
        }
        
        return $packagesWithName;
    }

    /**
     * @param $namespace
     * @return mixed
     * @throws \Exception
     */
    public function getVersion($namespace) {
        if (isset($this->packages[$namespace])) {
            return $this->packages[$namespace]['version'];
        }

        throw new \Exception('Package with namespace "' . $namespace . '" not found (Should be an existent composer package).');
    }
}
