<?php namespace Mascame\Artificer\Extension;

use Mascame\Arrayer;
use Mascame\Artificer\Options\AdminOption;

class Installer {

    /**
     * @var string
     */
    public $configFile;

    protected $config = [];

    public function __construct($configFile) {
        $this->configFile = $configFile;
        $this->config = $this->getConfig();
    }

    public function getConfig() {
        if (! empty($this->config)) return $this->config;

        $config = require $this->configFile;

        // Set defaults if empty
        if (!isset($config['installed'])) $config['installed'] = [];
        if (!isset($config['uninstalled'])) $config['uninstalled'] = [];

        return $config;
    }

    /**
     * @param $extensions
     * @return bool
     */
    public function handleExtensionChanges($extensions)
    {
        if (empty($extensions)) return;

        $configExtensions = array_merge($this->config['installed'], $this->config['uninstalled']);
        $added = array_diff($extensions, $configExtensions);
        $removed = array_diff($configExtensions, $extensions);

        $needsUpdate = (! empty($added) || ! empty($removed) || count($configExtensions) != count($extensions));

        if ($needsUpdate) $this->generateConfigFile($added, $removed);
    }

    public function isInstalled($name) {
        return isset($this->config['installed']) && in_array($name, $this->config['installed']);
    }

    public function install($extension) {
        return $this->action($extension, 'install');
    }

    public function uninstall($extension) {
        return $this->action($extension, 'uninstall');
    }

    /**
     * @param $extension
     * @param $operation
     * @return bool
     * @throws \Exception
     */
    protected function action($extension, $operation)
    {
        if ($operation == 'install') {
            $from = 'uninstalled';
            $to = 'installed';
            $message = 'Successfully installed <b>' . $extension . '</b>';
        } else {
            $from = 'installed';
            $to = 'uninstalled';
            $message = 'Successfully uninstalled <b>' . $extension . '</b>';
        }

        if (isset($this->config[$to])) {
            if (in_array($extension, $this->config[$to])) {
//                Notification::danger('Can not ' . $operation . ' ' . $plugin . ', maybe it is already ' . $from);

                return false;
            }
        }

        return $this->makeOperation($this->config, $extension, $from, $to, $message);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $message
     */
    protected function makeOperation($extensions, $plugin, $from, $to, $message)
    {
        try {
            $this->modifyFile($this->configFile, $extensions, $plugin, $from, $to);

//            Notification::success($message);
        } catch (\Exception $e) {
            throw new \Exception("Failed to modify plugins config");
        }

        return true;
    }

    /**
     * @param $file
     * @param $extensions
     * @param $plugin
     * @param $to
     * @throws \Exception
     */
    protected function modifyFile($file, $extensions, $plugin, $from, $to)
    {
        if (($key = array_search($plugin, $extensions[$from])) !== false) {
            unset($extensions[$from][$key]);
            $extensions[$to][] = $plugin;

            if (!file_exists($file)) {
                throw new \Exception("File not found {$file}");
            }

            $result = \File::put($file, (new Arrayer\Builder\ArrayBuilder($extensions))->getContent());

            if ($result) $this->config = $extensions;
        }

        return false;
    }

    /**
     * @param $added
     * @param $removed
     */
    protected function generateConfigFile($added, $removed)
    {
        foreach ($added as $name) {
            $this->config['uninstalled'][] = $name;
        }

        foreach ($removed as $name) {
            if (($key = array_search($name, $this->config['installed'])) !== false) {
                unset($this->config['installed'][$key]);
                continue;
            }

            if (($key = array_search($name, $this->config['uninstalled'])) !== false) {
                unset($this->config['uninstalled'][$key]);
                continue;
            }
        }

        $builder = new Arrayer\Builder\ArrayBuilder($this->config);
        \File::put($this->configFile, $builder->getContent());
    }
}
