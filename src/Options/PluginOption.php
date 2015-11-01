<?php namespace Mascame\Artificer\Options;

use Config;

class PluginOption
{

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $configFile;

    /**
     * @param $namespace
     */
    public function __construct($namespace, $configFile)
    {
        $exploded_namespace = explode('/', $namespace);
        $this->namespace = end($exploded_namespace) . '::';
        $this->configFile = $configFile;
    }

    /**
     * @param string $plugin
     * @param string $key
     */
    public function get($key = null, $configFile = null)
    {
        return Config::get($this->getPrefix($configFile) . $key);
    }

    /**
     * @param string $plugin
     */
    public function has($key = '', $configFile = null)
    {
        return Config::has($this->getPrefix($configFile) . $key);
    }

    /**
     * @param string $plugin
     */
    public function set($key, $value, $configFile = null)
    {
        Config::set($this->getPrefix($configFile) . $key, $value);
    }

    /**
     * @param string $key
     */
    public function all($key = null, $configFile = null)
    {
        return Config::get($this->getPrefix($configFile) . $key);
    }

    /**
     * @param null $configFile
     * @return null|string
     */
    protected function getFile($configFile = null)
    {
        return ($configFile) ? $configFile : $this->configFile;
    }

    /**
     * @param $file
     */
    public function setConfigFile($file)
    {
        $this->configFile = $file;
    }

    public function getPrefix($configFile = null)
    {
        return $this->namespace . $this->getFile($configFile) . '.';
    }
}