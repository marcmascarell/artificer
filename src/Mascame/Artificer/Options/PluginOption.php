<?php namespace Mascame\Artificer\Options;

use Config;

class PluginOption {

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
    public function __construct($namespace, $configFile) {
        $exploded_namespace = explode('/', $namespace);
        $this->namespace = end($exploded_namespace) . '::';    
        $this->configFile = $configFile;
    }

    /**
     * @param null $key
     * @param null $configFile
     * @return mixed
     */
	public function get($key = null, $configFile = null)
	{
        return Config::get($this->namespace . $this->getFile($configFile) . '.' . $key);
	}

    /**
     * @param string $key
     * @param null $configFile
     * @return bool
     */
	public function has($key = '', $configFile = null)
	{
		return Config::has($this->namespace . $this->getFile($configFile) . '.' . $key);
	}

    /**
     * @param $key
     * @param $value
     * @param null $configFile
     */
	public function set($key, $value, $configFile = null)
	{
        Config::set($this->namespace . $this->getFile($configFile) . '.' . $key, $value);
	}

    /**
     * @param null $key
     * @param null $configFile
     * @return mixed
     */
	public function all($key = null, $configFile = null)
	{
		return Config::get($this->namespace . $this->getFile($configFile) . '.' . $key);
	}

    /**
     * @param null $configFile
     * @return null|string
     */
    protected function getFile($configFile = null) {
        return ($configFile) ? $configFile : $this->configFile;
    }

    /**
     * @param $file
     */
    public function setConfigFile($file) {
        $this->configFile = $file;
    }
}