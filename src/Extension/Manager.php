<?php

namespace Mascame\Artificer\Extension;

use Mascame\Extender\Event\EventInterface;
use Mascame\Extender\Booter\BooterInterface;
use Mascame\Extender\Installer\InstallerInterface;

class Manager extends \Mascame\Extender\Manager
{
    /**
     * @var \Mascame\Artificer\Plugin\Manager|\Mascame\Artificer\Widget\Manager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $type = 'extension';

    /**
     * Composer packages.
     *
     * @var array
     */
    protected $composerPackages = [];

    protected static $packages = [];

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
    ) {
        parent::__construct($installer, $booter, $eventDispatcher);

        $this->composerPackages = $this->getComposerPackages();
    }

    public function getPackages()
    {
        return self::$packages;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Todo: refactor.
     *
     * @param $package
     * @param array|string $plugins
     * @return bool
     * @throws \Exception
     */
    public function add($package, $plugins)
    {
        if (! $this->isValidPackageName($package)) {
            throw new \Exception('Extension namespace is mandatory and must be compliant to "vendor/package". Provided: '.$package);
        }

        // Convert to array
        if (! is_array($plugins)) {
            $plugins = [$plugins];
        }

        // Get package info
        if (! isset(self::$packages[$package])) {
            self::$packages[$package] = new \stdClass();

            if (isset($this->composerPackages[$package])) {
                $packageData = $this->composerPackages[$package];
            } else {
                $packageData = [
                    'name' => $package,
                    'version' => 'none',
                    'description' => null,
                    'authors' => [
                        [
                            'name' => 'Anonymous',
                            'email' => 'anonymous@example.com',
                        ],
                    ],
                ];
            }

            self::$packages[$package] = (object) $packageData;
        }

        foreach ($plugins as $pluginName) {
            // Group the extensions provided under the package namespace
            self::$packages[$package]->provides[$this->type][] = $pluginName;

            parent::add($pluginName, function () use ($pluginName, $package) {

                /*
                 * First we try to resolve the plugin within the App Container
                 */
                try {
                    $plugin = app($pluginName);
                } catch (\ReflectionException $e) {
                    $plugin = new $pluginName;
                }

                $plugin->package = $package;

                return $plugin;
            });
        }

        return true;
    }

    /**
     * vendor/name.
     *
     * @param $name
     * @return bool
     */
    protected function isValidPackageName($name)
    {
        $regex = '/^[\\w-]+\\/[\\w-]+$/';

        preg_match($regex, $name, $matches);

        return count($matches) > 0;
    }

    /**
     * @return array
     */
    protected function getComposerPackages()
    {
        $installedPackagesFile = config('admin.vendor_path').'/composer/installed.json';
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
    public function getVersion($namespace)
    {
        if (isset(self::$packages[$namespace])) {
            return self::$packages[$namespace]['version'];
        }

        throw new \Exception('Package with namespace "'.$namespace.'" not found (Should be an existent composer package).');
    }
}
